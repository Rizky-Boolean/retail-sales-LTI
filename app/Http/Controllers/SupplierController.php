<?php

namespace App\Http\Controllers;

use App\Models\Supplier;
use Illuminate\Http\Request;

class SupplierController extends Controller
{
    /**
     * Menampilkan daftar semua supplier.
     */
    public function index()
    {
        // Ambil data supplier terbaru, 10 data per halaman
        $suppliers = Supplier::latest()->paginate(10);
        
        // Kembalikan view 'suppliers.index' dan kirim data suppliers
        return view('suppliers.index', compact('suppliers'));
    }

    /**
     * Menampilkan form untuk membuat supplier baru.
     */
    public function create()
    {
        // Cukup tampilkan view 'suppliers.create'
        return view('suppliers.create');
    }

    /**
     * Menyimpan supplier baru ke database.
     */
    public function store(Request $request)
    {
        // Validasi input dari form
        $validated = $request->validate([
            'nama_supplier' => 'required|string|max:255',
            'alamat' => 'nullable|string',
            'kontak' => 'nullable|string|max:50',
        ]);

        // Buat data baru di database
        Supplier::create($validated);

        // Redirect kembali ke halaman index dengan pesan sukses
        return redirect()->route('suppliers.index')->with('success', 'Data supplier berhasil ditambahkan!');
    }

    /**
     * Menampilkan data spesifik dari satu supplier (tidak kita gunakan, tapi biarkan ada).
     */
    public function show(Supplier $supplier)
    {
        // Biasanya untuk halaman detail, kita bisa redirect ke edit saja
        return redirect()->route('suppliers.edit', $supplier->id);
    }

    /**
     * Menampilkan form untuk mengedit supplier.
     */
    public function edit(Supplier $supplier)
    {
        // Tampilkan view 'suppliers.edit' dan kirim data supplier yang akan diedit
        return view('suppliers.edit', compact('supplier'));
    }

    /**
     * Memperbarui data supplier di database.
     */
    public function update(Request $request, Supplier $supplier)
    {
        // Validasi input dari form edit
        $validated = $request->validate([
            'nama_supplier' => 'required|string|max:255',
            'alamat' => 'nullable|string',
            'kontak' => 'nullable|string|max:50',
        ]);

        // Update data supplier yang ada
        $supplier->update($validated);

        // Redirect kembali ke halaman index dengan pesan sukses
        return redirect()->route('suppliers.index')->with('success', 'Data supplier berhasil diperbarui!');
    }

    /**
     * Menghapus data supplier dari database.
     */
    public function destroy(Supplier $supplier)
    {
        // Hapus data
        $supplier->delete();

        // Redirect kembali ke halaman index dengan pesan sukses
        return redirect()->route('suppliers.index')->with('success', 'Data supplier berhasil dihapus!');
    }
}
