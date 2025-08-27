<?php

namespace App\Http\Controllers;

use App\Models\Cabang;
use App\Models\Distribusi;
use App\Models\Sparepart;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Validation\ValidationException;
use App\Models\User;
use App\Notifications\NewDistributionNotification;
use Illuminate\Support\Facades\Notification;
use App\Models\ActivityLog;

class DistribusiController extends Controller
{
    public function index(Request $request)
    {
        $query = Distribusi::with('cabangTujuan');

        // Terapkan filter pencarian
        $search = $request->input('search');
        if ($search) {
            $query->where(function($q) use ($search) {
                // Cari berdasarkan ID (tanpa prefix "DIST-")
                $q->where('id', 'like', "%{$search}%")
                  // Cari berdasarkan nama cabang tujuan melalui relasi
                  ->orWhereHas('cabangTujuan', function($subQ) use ($search) {
                      $subQ->where('nama_cabang', 'like', "%{$search}%");
                  });
            });
        }

        $distribusis = $query->latest()->paginate(10)->withQueryString();
        
        return view('distribusi.index', compact('distribusis'));
    }

    public function stokInduk(Request $request)
    {
        $query = Sparepart::query();

        $search = $request->input('search');
        if ($search) {
            $searchLower = strtolower($search);
            $query->where(function ($q) use ($searchLower) {
                $q->where(DB::raw('LOWER(kode_part)'), 'like', "%{$searchLower}%")
                ->orWhere(DB::raw('LOWER(nama_part)'), 'like', "%{$searchLower}%");
            });
        }

        $spareparts = $query->orderBy('nama_part')->paginate(20)->withQueryString();

        return view('distribusi.stok-induk', compact('spareparts'));
    }

    /**
     * Menampilkan detail satu transaksi distribusi.
     */
    public function show(Distribusi $distribusi)
    {
        $user = auth()->user();

        if ($user->role === 'admin_cabang' && $distribusi->cabang_id_tujuan != $user->cabang_id) {
            abort(403, 'ANDA TIDAK BERHAK MELIHAT DETAIL KIRIMAN INI.');
        }

        $distribusi->load('cabangTujuan', 'user', 'details.sparepart');
        
        return view('distribusi.show', compact('distribusi'));
    }

    /**
     * Menampilkan form untuk membuat distribusi baru.
     */
    public function create()
    {
        $cabangs = Cabang::active()->orderBy('nama_cabang')->get();
        $spareparts = Sparepart::active()->where('stok_induk', '>', 0)->orderBy('nama_part')->get();
        return view('distribusi.create', compact('cabangs', 'spareparts'));
    }

    /**
     * Menyimpan data distribusi baru ke database.
     */
        public function store(Request $request)
        {
            $validated = $request->validate([
                'tanggal_distribusi' => 'required|date',
                'cabang_id_tujuan' => 'required|exists:cabangs,id',
                'details' => 'required|array|min:1',
                'details.*.sparepart_id' => 'required|exists:spareparts,id',
                'details.*.qty' => 'required|integer|min:1',
            ]);

            try {
                $distribusi = DB::transaction(function () use ($validated) {
                    foreach ($validated['details'] as $item) {
                        $sparepart = Sparepart::find($item['sparepart_id']);
                        if ($sparepart->stok_induk < $item['qty']) {
                            throw ValidationException::withMessages([
                                'details' => "Stok untuk {$sparepart->nama_part} tidak mencukupi. Sisa stok: {$sparepart->stok_induk}.",
                            ]);
                        }
                    }

                    $distribusi = Distribusi::create([
                        'tanggal_distribusi' => $validated['tanggal_distribusi'],
                        'user_id' => auth()->id(),
                        'cabang_id_tujuan' => $validated['cabang_id_tujuan'],
                        'status' => 'dikirim',
                        'total_harga_modal' => 0,
                        'total_ppn_distribusi' => 0,
                        'total_harga_kirim' => 0,
                    ]);

                    $totalHargaModal = 0;
                    $totalHargaKirim = 0; // [TAMBAH] Variabel untuk total harga kirim

                    foreach ($validated['details'] as $item) {
                        $sparepart = Sparepart::find($item['sparepart_id']);
                        $qty = $item['qty'];

                        // [DIUBAH] Logika pengambilan harga
                        $hargaModal = $sparepart->harga_modal_terakhir;
                        $hargaKirim = $sparepart->harga_beli_terakhir; // Mengambil harga jual yang sudah di-markup

                        $distribusi->details()->create([
                            'sparepart_id' => $sparepart->id, 'qty' => $qty,
                            'harga_modal_satuan' => $hargaModal,
                            'harga_kirim_satuan' => $hargaKirim,
                        ]);

                        $sparepart->decrement('stok_induk', $qty);

                        // [DIUBAH] Akumulasi total yang benar
                        $totalHargaModal += $hargaModal * $qty;
                        $totalHargaKirim += $hargaKirim * $qty;
                    }

                    $distribusi->update([
                        'total_harga_modal' => $totalHargaModal,
                        'total_ppn_distribusi' => 0,
                        'total_harga_kirim' => $totalHargaKirim,
                    ]);

                    return $distribusi;
                });

                $targetUsers = User::where('role', 'admin_gudang_cabang')
                                    ->where('cabang_id', $distribusi->cabang_id_tujuan)
                                    ->get();

                if ($targetUsers->isNotEmpty()) {
                    Notification::send($targetUsers, new NewDistributionNotification($distribusi));
                }
                
                return redirect()->route('distribusi.index')->with('success', 'Data distribusi berhasil disimpan dan notifikasi telah dikirim!');
            } catch (ValidationException $e) {
                return redirect()->back()->withInput()->withErrors($e->errors());
            } catch (\Exception $e) {
                return redirect()->back()->withInput()->with('error', 'Terjadi kesalahan: ' . $e->getMessage());
            }
        }
}