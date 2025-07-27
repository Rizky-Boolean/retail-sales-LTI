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

class DistribusiController extends Controller
{
    /**
     * Menampilkan histori transaksi distribusi.
     */
    public function index()
    {
        $distribusis = Distribusi::with('cabangTujuan')->latest()->paginate(10);
        return view('distribusi.index', compact('distribusis'));
    }
    public function search(Request $request)
    {
        $search = $request->get('search');
        
        $distribusis = Distribusi::with('cabangTujuan')
            ->where(function($query) use ($search) {
                $query->where('id', 'LIKE', "%{$search}%")
                    ->orWhereHas('cabangTujuan', function($q) use ($search) {
                        $q->where('nama_cabang', 'LIKE', "%{$search}%");
                    });
            })
            ->latest()
            ->get();
        
        return response()->json($distribusis);
    }
    public function searchStokInduk(Request $request)
    {
        $search = $request->get('search');
        
        $spareparts = Sparepart::where(function($query) use ($search) {
            $query->where('kode_part', 'LIKE', "%{$search}%")
                ->orWhere('nama_part', 'LIKE', "%{$search}%")
                ->orWhere('satuan', 'LIKE', "%{$search}%");
        })
        ->orderBy('nama_part')
        ->get();
        
        return response()->json($spareparts);
    }

    /**
     * Menampilkan detail satu transaksi distribusi.
     */
    public function show(Distribusi $distribusi)
    {
        $user = auth()->user();

        // Jika user adalah admin cabang, cek apakah kiriman ini untuk cabangnya.
        if ($user->role === 'admin_cabang' && $distribusi->cabang_id_tujuan != $user->cabang_id) {
            // Jika bukan, tolak akses.
            abort(403, 'ANDA TIDAK BERHAK MELIHAT DETAIL KIRIMAN INI.');
        }

        // Eager load semua relasi yang dibutuhkan
        $distribusi->load('cabangTujuan', 'user', 'details.sparepart');
        
        return view('distribusi.show', compact('distribusi'));
    }

    /**
     * Menampilkan form untuk membuat distribusi baru.
     */
    public function create()
    {
        $cabangs = \App\Models\Cabang::orderBy('nama_cabang')->get();
        $spareparts = \App\Models\Sparepart::where('stok_induk', '>', 0)->orderBy('nama_part')->get();
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
            // Simpan hasil transaction ke variabel
            $distribusi = DB::transaction(function () use ($validated) {
                // 1. Validasi stok terlebih dahulu sebelum melakukan operasi apapun
                foreach ($validated['details'] as $item) {
                    $sparepart = Sparepart::find($item['sparepart_id']);
                    if ($sparepart->stok_induk < $item['qty']) {
                        throw ValidationException::withMessages([
                            'details' => "Stok untuk {$sparepart->nama_part} tidak mencukupi. Sisa stok: {$sparepart->stok_induk}.",
                        ]);
                    }
                }

                // 2. Buat record header distribusi (total masih 0)
                $distribusi = Distribusi::create([
                    'tanggal_distribusi' => $validated['tanggal_distribusi'],
                    'user_id' => auth()->id(),
                    'cabang_id_tujuan' => $validated['cabang_id_tujuan'],
                    'status' => 'dikirim',
                    'total_harga_modal' => 0,
                    'total_ppn_distribusi' => 0, // PPN akan selalu 0
                    'total_harga_kirim' => 0,
                ]);

                $totalHargaModal = 0;

                // Proses setiap item detail
                foreach ($validated['details'] as $item) {
                    $sparepart = Sparepart::find($item['sparepart_id']);
                    $qty = $item['qty'];
                    $hargaModal = $sparepart->harga_modal_terakhir;
                    $hargaKirim = $hargaModal;

                    $distribusi->details()->create([
                        'sparepart_id' => $sparepart->id, 'qty' => $qty,
                        'harga_modal_satuan' => $hargaModal,
                        'harga_kirim_satuan' => $hargaKirim,
                    ]);

                    $sparepart->decrement('stok_induk', $qty);

                    $totalHargaModal += $hargaModal * $qty;
                }

                $distribusi->update([
                    'total_harga_modal' => $totalHargaModal,
                    'total_ppn_distribusi' => 0, // Hardcode PPN menjadi 0
                    'total_harga_kirim' => $totalHargaModal, // Total kirim = total modal
                ]);

                return $distribusi;
            });

            // [START] Logika untuk Mengirim Notifikasi
            $targetUsers = User::where('role', 'admin_gudang_cabang')
                               ->where('cabang_id', $distribusi->cabang_id_tujuan)
                               ->get();

            if ($targetUsers->isNotEmpty()) {
                Notification::send($targetUsers, new NewDistributionNotification($distribusi));
            }
            // [END] Logika untuk Mengirim Notifikasi

            return redirect()->route('distribusi.index')->with('success', 'Data distribusi berhasil disimpan dan notifikasi telah dikirim!');
        } catch (ValidationException $e) {
            return redirect()->back()->withInput()->withErrors($e->errors());
        } catch (\Exception $e) {
            return redirect()->back()->withInput()->with('error', 'Terjadi kesalahan: ' . $e->getMessage());
        }
    }
    public function stokInduk()
    {
        // Ambil semua data sparepart, urutkan berdasarkan nama, dan paginasi
        $spareparts = Sparepart::orderBy('nama_part')->paginate(20);

        return view('distribusi.stok-induk', compact('spareparts'));
    }
}
