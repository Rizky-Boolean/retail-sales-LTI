<?php

namespace App\Http\Controllers;

use App\Models\Distribusi;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use App\Models\Cabang;
use App\Notifications\ShipmentReceivedNotification;
use Illuminate\Support\Facades\Notification;
use Illuminate\Support\Facades\DB;
use App\Notifications\ShipmentRejectedNotification;
use Illuminate\Support\Facades\Log;
use App\Models\User;
use App\Models\ActivityLog;

class CabangController extends Controller
{
    // =================================================================
    // METHOD UNTUK MANAJEMEN CABANG (SUPER ADMIN)
    // =================================================================
    
    public function index(Request $request)
    {
        // Query dasar untuk cabang aktif
        $query = Cabang::query()->where('is_active', true);

        // Terapkan filter pencarian jika ada
        $search = $request->input('search');
        if ($search) {
            $query->where(function($q) use ($search) {
                $q->where('nama_cabang', 'like', "%{$search}%")
                  ->orWhere('alamat', 'like', "%{$search}%");
            });
        }

        // Ambil data dengan pagination dan sertakan query string
        $cabangs = $query->latest()->paginate(10)->withQueryString();
        
        return view('cabangs.index', compact('cabangs'));
    }

    public function create()
    {
        return view('cabangs.create');
    }

    public function store(Request $request)
    {
        $validated = $request->validate([
            'nama_cabang' => 'required|string|max:255',
            'alamat' => 'nullable|string',
        ]);

        Cabang::create($validated);

        return redirect()->route('cabangs.index')->with('success', 'Cabang baru berhasil dibuat.');
    }

    public function show(Cabang $cabang)
    {
        // Rute 'show' akan langsung diarahkan ke 'edit'
        return redirect()->route('cabangs.edit', $cabang);
    }

    public function edit(Cabang $cabang)
    {
        return view('cabangs.edit', compact('cabang'));
    }

    public function update(Request $request, Cabang $cabang)
    {
        $request->validate([
            'nama_cabang' => 'required|string|max:255',
            'alamat' => 'nullable|string',
        ]);

        $cabang->update($request->all());

        return redirect()->route('cabangs.index')->with('success', 'Data cabang berhasil diperbarui.');
    }
    public function inactive()
    {
        $cabangs = Cabang::where('is_active', false)->latest()->paginate(10);
        return view('cabangs.inactive', compact('cabangs'));
    }

    /**
     * [BARU] Mengubah status aktif/nonaktif.
     */
    public function toggleStatus(Cabang $cabang)
    {
        $cabang->is_active = !$cabang->is_active;
        $actionText = $cabang->is_active ? 'diaktifkan' : 'dinonaktifkan';
        
        Cabang::withoutEvents(function () use ($cabang) {
            $cabang->save();
        });

        // Mencatat aktivitas
        ActivityLog::create([
            'user_id'     => auth()->id(),
            'description' => "Cabang '{$cabang->nama_cabang}' telah {$actionText}.",
            'ip_address'  => request()->ip(),
        ]);

        $message = "Data cabang berhasil {$actionText}.";
        return redirect()->back()->with('success', $message);
    }


    // =================================================================
    // METHOD UNTUK FITUR ADMIN GUDANG CABANG
    // =================================================================

    public function stokIndex()
    {
        $user = Auth::user();
        $spareparts = $user->cabang->spareparts()->paginate(15);
        return view('cabang.stok.index', compact('spareparts'));
    }
        public function searchStok(Request $request)
    {
        try {
            $user = auth()->user();
            $search = $request->get('search');
            
            $spareparts = $user->cabang->spareparts()
                ->where(function($query) use ($search) {
                    $query->where('spareparts.kode_part', 'LIKE', "%{$search}%")
                        ->orWhere('spareparts.nama_part', 'LIKE', "%{$search}%");
                })
                ->select('spareparts.*', 'cabang_sparepart.stok')
                ->get();
                
            return response()->json($spareparts);
        } catch (\Exception $e) {
            return response()->json(['error' => $e->getMessage()], 500);
        }
    }

    public function penerimaanIndex()
    {
        $user = Auth::user();
        $kirimanMasuk = Distribusi::where('cabang_id_tujuan', $user->cabang_id)
                                    ->with('user')
                                    ->latest()
                                    ->paginate(10);

        return view('cabang.penerimaan.index', compact('kirimanMasuk'));
    }
    public function searchPenerimaan(Request $request)
    {
        try {
            $user = auth()->user();
            $search = $request->get('search');
            
            $kirimanMasuk = Distribusi::where('cabang_id_tujuan', $user->cabang_id)
                ->where(function($query) use ($search) {
                    $query->where('id', 'LIKE', "%{$search}%")
                        ->orWhereHas('user', function($q) use ($search) {
                            $q->where('name', 'LIKE', "%{$search}%");
                        });
                })
                ->with('user')
                ->latest()
                ->get();
                
            return response()->json($kirimanMasuk);
        } catch (\Exception $e) {
            return response()->json(['error' => $e->getMessage()], 500);
        }
    }

    public function terimaBarang(Distribusi $distribusi)
    {
        if ($distribusi->cabang_id_tujuan !== Auth::user()->cabang_id || $distribusi->status !== 'dikirim') {
            abort(403, 'AKSES DITOLAK.');
        }

        DB::transaction(function () use ($distribusi) {
            foreach ($distribusi->details as $detail) {
                $cabang = $distribusi->cabangTujuan;
                $stokCabang = $cabang->spareparts()->where('sparepart_id', $detail->sparepart_id)->first();

                if ($stokCabang) {
                    $cabang->spareparts()->updateExistingPivot($detail->sparepart_id, ['stok' => $stokCabang->pivot->stok + $detail->qty]);
                } else {
                    $cabang->spareparts()->attach($detail->sparepart_id, ['stok' => $detail->qty]);
                }
            }
            $distribusi->update(['status' => 'diterima']);
        });
        $sender = $distribusi->user; 
        $superAdmins = User::where('role', 'super_admin')->get();
        $adminsGudangInduk = User::where('role', 'admin_gudang_induk')->get();
        $recipients = collect([$sender])
                        ->merge($superAdmins)
                        ->merge($adminsGudangInduk)
                        ->filter()
                        ->unique('id');

        if ($recipients->isNotEmpty()) {
            Notification::send($recipients, new ShipmentReceivedNotification($distribusi, Auth::user()->name));
        }

        return redirect()->route('cabang.penerimaan.index')->with('success', 'Barang berhasil diterima dan stok telah ditambahkan.');
    }

    public function tolakBarang(Request $request, Distribusi $distribusi)
    {
        if ($distribusi->cabang_id_tujuan !== Auth::user()->cabang_id || $distribusi->status !== 'dikirim') {
            abort(403, 'AKSES DITOLAK.');
        }

        $request->validate(['alasan_penolakan' => 'required|string|max:255']);

        DB::transaction(function () use ($distribusi, $request) {
            foreach ($distribusi->details as $detail) {
                $detail->sparepart->increment('stok_induk', $detail->qty);
            }
            $distribusi->update([
                'status' => 'ditolak',
                'alasan_penolakan' => $request->alasan_penolakan,
            ]);
        });
        $sender = $distribusi->user;
        $superAdmins = User::where('role', 'super_admin')->get();
        $adminsGudangInduk = User::where('role', 'admin_gudang_induk')->get(); // Ambil semua admin gudang induk

        $recipients = collect([$sender])
                        ->merge($superAdmins)
                        ->merge($adminsGudangInduk)
                        ->filter()
                        ->unique('id');

        if ($recipients->isNotEmpty()) {
            Notification::send($recipients, new ShipmentRejectedNotification($distribusi, Auth::user()->name));
        }

        return redirect()->route('cabang.penerimaan.index')->with('success', 'Kiriman berhasil ditolak dan stok telah dikembalikan ke gudang induk.');
    }
}
