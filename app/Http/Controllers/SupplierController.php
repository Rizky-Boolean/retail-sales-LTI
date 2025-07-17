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
        $validated = $request->validate([
            'nama_supplier' => 'required|string|max:255',
            'alamat' => 'nullable|string',
            'kontak' => ['nullable', 'string', 'regex:/^[\+]?[0-9\s\-]+$/', 'max:20'],
        ], [
            'kontak.regex' => 'Format kontak tidak valid. Hanya boleh berisi angka, spasi, tanda hubung (-), dan tanda tambah (+) di awal.'
        ]);

        Supplier::create($validated);

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
        // [UBAH] Tambahkan aturan regex untuk validasi kontak
        $validated = $request->validate([
            'nama_supplier' => 'required|string|max:255',
            'alamat' => 'nullable|string',
            'kontak' => ['nullable', 'string', 'regex:/^[\+]?[0-9\s\-]+$/', 'max:20'],
        ], [
            'kontak.regex' => 'Format kontak tidak valid. Hanya boleh berisi angka, spasi, tanda hubung (-), dan tanda tambah (+) di awal.'
        ]);

        $supplier->update($validated);

        return redirect()->route('suppliers.index')->with('success', 'Data supplier berhasil diperbarui!');
    }
    /**
     * Menghapus data supplier dari database.
     */
    public function destroy(Supplier $supplier)
    {
        // Cek apakah supplier ini punya relasi dengan stok_masuks
        if ($supplier->stokMasuks()->exists()) {
            return redirect()->route('suppliers.index')
                           ->with('error', 'Gagal menghapus! Supplier ini sudah memiliki riwayat transaksi stok masuk.');
        }

        try {
            $supplier->delete();
            return redirect()->route('suppliers.index')
                           ->with('success', 'Supplier berhasil dihapus.');
        } catch (\Exception $e) {
            return redirect()->route('suppliers.index')
                           ->with('error', 'Terjadi kesalahan saat menghapus supplier.');
        }
    }
    /**
     * [BARU] Menampilkan daftar supplier yang sudah di-soft delete.
     */
    public function trash()
    {
        $suppliers = Supplier::onlyTrashed()->paginate(10);
        return view('suppliers.trash', compact('suppliers'));
    }

    /**
     * [BARU] Mengembalikan data supplier dari trash.
     */
    public function restore($id)
    {
        $supplier = Supplier::onlyTrashed()->findOrFail($id);
        $supplier->restore();
        return redirect()->route('suppliers.trash')->with('success', 'Data supplier berhasil dikembalikan.');
    }

    /**
     * [BARU] Menghapus data supplier secara permanen.
     */
    public function forceDelete($id)
    {
        $supplier = Supplier::onlyTrashed()->findOrFail($id);
        $supplier->forceDelete();
        return redirect()->route('suppliers.trash')->with('success', 'Data supplier berhasil dihapus permanen.');
    }
}