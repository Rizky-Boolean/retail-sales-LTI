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

class CabangController extends Controller
{
    // =================================================================
    // METHOD UNTUK MANAJEMEN CABANG (SUPER ADMIN)
    // =================================================================
    
    public function index()
    {
        $cabangs = Cabang::latest()->paginate(10);
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

    public function destroy(Cabang $cabang)
    {
        if ($cabang->users()->exists()) {
            return redirect()->route('cabangs.index')->with('error', 'Gagal menghapus! Masih ada user yang terdaftar di cabang ini.');
        }
        if ($cabang->distribusis()->exists()) {
            return redirect()->route('cabangs.index')->with('error', 'Gagal menghapus! Cabang ini memiliki riwayat transaksi distribusi.');
        }
        if ($cabang->penjualans()->exists()) {
            return redirect()->route('cabangs.index')->with('error', 'Gagal menghapus! Cabang ini memiliki riwayat transaksi penjualan.');
        }
        
        try {
            $cabang->delete();
            return redirect()->route('cabangs.index')->with('success', 'Cabang berhasil dihapus.');
        } catch (\Exception $e) {
            return redirect()->route('cabangs.index')->with('error', 'Terjadi kesalahan saat menghapus cabang.');
        }
    }
    
    public function trash()
    {
        $cabangs = Cabang::onlyTrashed()->paginate(10);
        return view('cabangs.trash', compact('cabangs'));
    }

    public function restore($id)
    {
        $cabang = Cabang::onlyTrashed()->findOrFail($id);
        $cabang->restore();
        return redirect()->route('cabangs.trash')->with('success', 'Data cabang berhasil dikembalikan.');
    }

    public function forceDelete($id)
    {
        $cabang = Cabang::onlyTrashed()->findOrFail($id);
        $cabang->forceDelete();
        return redirect()->route('cabangs.trash')->with('success', 'Data cabang berhasil dihapus permanen.');
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

    public function penerimaanIndex()
    {
        $user = Auth::user();
        $kirimanMasuk = Distribusi::where('cabang_id_tujuan', $user->cabang_id)
                                    ->with('user')
                                    ->latest()
                                    ->paginate(10);

        return view('cabang.penerimaan.index', compact('kirimanMasuk'));
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
        if ($sender) {
            Notification::send($sender, new ShipmentReceivedNotification($distribusi, Auth::user()->name));
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
        
        $sender = $distribusi->user; // Dapatkan user yang membuat distribusi
        $rejectorName = Auth::user()->name; // Nama admin cabang yang menolak

        if ($sender) {
            // Kirim notifikasi ke pengirim
            Notification::send($sender, new ShipmentRejectedNotification($distribusi, $rejectorName));
        }
        // [END] Logika untuk Mengirim Notifikasi Penolakan
        return redirect()->route('cabang.penerimaan.index')->with('success', 'Kiriman berhasil ditolak dan stok telah dikembalikan ke gudang induk.');
    }
}
