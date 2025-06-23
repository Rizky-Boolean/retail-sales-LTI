<?php

namespace App\Http\Controllers;

use App\Models\Sparepart;
use Illuminate\Http\Request;

class SparepartController extends Controller
{
    /**
     * Menampilkan daftar semua sparepart.
     */
    public function index()
    {
        $spareparts = Sparepart::latest()->paginate(10);
        return view('spareparts.index', compact('spareparts'));
    }

    /**
     * Menampilkan form untuk membuat sparepart baru.
     */
    public function create()
    {
        $satuans = ['Pcs', 'Set', 'Unit', 'Liter', 'Botol', 'Box', 'Roll'];
        
        // Kirim daftar tersebut ke view
        return view('spareparts.create', compact('satuans'));
    }

    /**
     * Menyimpan sparepart baru ke database. (LOGIKA DIPERBARUI)
     */
    public function store(Request $request)
    {
        // 1. Validasi input baru, tidak ada lagi harga_jual
        $validated = $request->validate([
            'kode_part' => 'required|unique:spareparts,kode_part|max:255',
            'nama_part' => 'required|max:255',
            'satuan' => 'required|max:50',
            'markup_persen' => 'required|numeric|min:0', // Validasi markup
        ]);

        // 2. Simpan data baru. Harga jual defaultnya 0 karena belum ada harga beli.
        Sparepart::create($validated);

        // 3. Redirect ke halaman index dengan pesan sukses
        return redirect()->route('spareparts.index')->with('success', 'Data sparepart berhasil ditambahkan!');
    }


    /**
     * Menampilkan form untuk mengedit sparepart.
     */
    public function edit(Sparepart $sparepart)
    {
        $satuans = ['Pcs', 'Set', 'Unit', 'Liter', 'Botol', 'Box', 'Roll'];
        
        // Kirim daftar dan data sparepart ke view
        return view('spareparts.edit', compact('sparepart', 'satuans'));
    }

    /**
     * Memperbarui data sparepart di database. (LOGIKA DIPERBARUI)
     */
    public function update(Request $request, Sparepart $sparepart)
    {
        // 1. Validasi input baru
        $validated = $request->validate([
            'kode_part' => 'required|max:255|unique:spareparts,kode_part,' . $sparepart->id,
            'nama_part' => 'required|max:255',
            'satuan' => 'required|max:50',
            'markup_persen' => 'required|numeric|min:0', // Validasi markup
        ]);

        // 2. Hitung ulang Harga Jual berdasarkan markup baru dan harga beli terakhir
        $hargaBeliTerakhir = $sparepart->harga_beli_terakhir;
        $markupPersen = $validated['markup_persen'];
        
        $markupAmount = $hargaBeliTerakhir * ($markupPersen / 100);
        $newHargaJual = $hargaBeliTerakhir + $markupAmount;

        // 3. Gabungkan data terhitung ke data tervalidasi
        $validated['harga_jual'] = $newHargaJual;

        // 4. Update data di database
        $sparepart->update($validated);

        // 5. Redirect ke halaman index dengan pesan sukses
        return redirect()->route('spareparts.index')->with('success', 'Markup & Harga Jual berhasil diperbarui!');
    }

    /**
     * Menghapus data sparepart dari database.
     */
    public function destroy(Sparepart $sparepart)
    {
        $sparepart->delete();
        return redirect()->route('spareparts.index')->with('success', 'Data sparepart berhasil dihapus!');
    }
}
