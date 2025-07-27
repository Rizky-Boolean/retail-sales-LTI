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
        $suppliers = Supplier::active()->latest()->paginate(10);
        return view('suppliers.index', compact('suppliers'));
    }
    public function search(Request $request)
    {
        $search = $request->get('search');
        
        $suppliers = Supplier::where('nama_supplier', 'LIKE', "%{$search}%")
            ->orWhere('alamat', 'LIKE', "%{$search}%")
            ->orWhere('kontak', 'LIKE', "%{$search}%")
            ->get();
            
        return response()->json($suppliers);
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
        return redirect()->route('suppliers.edit', $supplier->id);
    }

    /**
     * Menampilkan form untuk mengedit supplier.
     */
    public function edit(Supplier $supplier)
    {
        return view('suppliers.edit', compact('supplier'));
    }

    /**
     * Memperbarui data supplier di database.
     */
    public function update(Request $request, Supplier $supplier)
    {
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
    public function inactive()
    {
        $suppliers = Supplier::where('is_active', false)->latest()->paginate(10);
        return view('suppliers.inactive', compact('suppliers'));
    }

    /**
     * [BARU] Mengubah status aktif/nonaktif.
     */
    public function toggleStatus(Supplier $supplier)
    {
        $supplier->is_active = !$supplier->is_active;
        $supplier->save();
        $message = $supplier->is_active ? 'Data supplier berhasil diaktifkan.' : 'Data supplier berhasil dinonaktifkan.';
        return redirect()->back()->with('success', $message);
    }
}