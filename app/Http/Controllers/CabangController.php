<?php

namespace App\Http\Controllers;

use App\Models\Distribusi;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use App\Models\Cabang;

class CabangController extends Controller
{
        // === METHOD UNTUK MANAJEMEN CABANG (Admin Induk) ===
    public function index()
    {
        $cabangs = Cabang::latest()->paginate(10);
        return view('cabangs.index', compact('cabangs'));
    }

    /**
     * [BARU] Menampilkan form untuk membuat cabang baru.
     */
    public function create()
    {
        return view('cabangs.create');
    }

    /**
     * [BARU] Menyimpan cabang baru ke database.
     */
    public function store(Request $request)
    {
        $validated = $request->validate([
            'nama_cabang' => 'required|string|max:255',
            'alamat' => 'nullable|string',
        ]);

        Cabang::create($validated);

        return redirect()->route('cabangs.index')->with('success', 'Cabang baru berhasil dibuat.');
    }

    /**
     * [BARU] Menampilkan form untuk mengedit cabang.
     */
    public function edit(Cabang $cabang)
    {
        return view('cabangs.edit', compact('cabang'));
    }

    /**
     * [BARU] Memperbarui data cabang di database.
     */
    public function update(Request $request, Cabang $cabang)
    {
        $request->validate([
            'nama_cabang' => 'required|string|max:255',
            'alamat' => 'nullable|string',
        ]);

        $cabang->update($request->all());

        return redirect()->route('cabangs.index')->with('success', 'Data cabang berhasil diperbarui.');
    }

    /**
     * [BARU] Menghapus data cabang.
     */
    public function destroy(Cabang $cabang)
    {
        // Cek relasi dengan Users
        if ($cabang->users()->exists()) {
            return redirect()->route('cabangs.index')
                           ->with('error', 'Gagal menghapus! Masih ada user yang terdaftar di cabang ini.');
        }

        // Cek relasi dengan Distribusi
        if ($cabang->distribusis()->exists()) {
            return redirect()->route('cabangs.index')
                           ->with('error', 'Gagal menghapus! Cabang ini memiliki riwayat transaksi distribusi.');
        }

        // Cek relasi dengan Penjualan
        if ($cabang->penjualans()->exists()) {
            return redirect()->route('cabangs.index')
                           ->with('error', 'Gagal menghapus! Cabang ini memiliki riwayat transaksi penjualan.');
        }
        
        try {
            $cabang->delete();
            return redirect()->route('cabangs.index')
                           ->with('success', 'Cabang berhasil dihapus.');
        } catch (\Exception $e) {
            return redirect()->route('cabangs.index')
                           ->with('error', 'Terjadi kesalahan saat menghapus cabang.');
        }
    }
    public function stokIndex()
    {
        $user = Auth::user();
        $spareparts = $user->cabang->spareparts()->paginate(15);
        return view('cabang.stok.index', compact('spareparts'));
    }

    /**
     * [BARU] Menampilkan daftar kiriman barang yang masuk untuk cabang.
     */
    public function penerimaanIndex()
    {
        $user = Auth::user();
        
        // Ambil data distribusi yang statusnya masih 'dikirim' ke cabang user ini
        $kirimanMasuk = Distribusi::where('cabang_id_tujuan', $user->cabang_id)
                                    ->where('status', 'dikirim')
                                    ->with('user') // Ambil juga data pengirim (Admin Induk)
                                    ->latest()
                                    ->paginate(10);

        return view('cabang.penerimaan.index', compact('kirimanMasuk'));
    }

    /**
     * [BARU] Memproses aksi penerimaan barang.
     */
    public function terimaBarang(Distribusi $distribusi)
    {
        // Pastikan user yang mencoba menerima barang adalah dari cabang tujuan
        if ($distribusi->cabang_id_tujuan !== Auth::user()->cabang_id) {
            abort(403, 'AKSES DITOLAK.');
        }

        // Update status distribusi menjadi 'diterima'
        $distribusi->update(['status' => 'diterima']);

        return redirect()->route('cabang.penerimaan.index')
                         ->with('success', 'Barang dari kiriman #DIST-'.$distribusi->id.' telah berhasil diterima.');
    }
}
