<?php

namespace App\Http\Controllers;

use App\Models\Supplier;
use Illuminate\Http\Request;
use App\Models\ActivityLog;


class SupplierController extends Controller
{
    /**
     * Menampilkan daftar semua supplier, termasuk menangani pencarian dan pagination.
     */
    public function index(Request $request)
    {
        // Mulai query untuk supplier yang aktif
        $query = Supplier::query()->where('is_active', true);

        // [PERUBAHAN] Terapkan filter pencarian jika ada input dari URL
        $search = $request->input('search');
        if ($search) {
            $query->where(function($q) use ($search) {
                $q->where('nama_supplier', 'like', "%{$search}%")
                  ->orWhere('alamat', 'like', "%{$search}%")
                  ->orWhere('kontak', 'like', "%{$search}%");
            });
        }

        // [PERUBAHAN] Ambil data dengan pagination dan sertakan query string (untuk search)
        $suppliers = $query->latest()->paginate(10)->withQueryString();

        return view('suppliers.index', compact('suppliers'));
    }

    public function create()
    {
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
     * Menampilkan data spesifik dari satu supplier.
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

    /**
     * Menampilkan data supplier yang tidak aktif.
     */
    public function inactive()
    {
        // [PERBAIKAN KECIL] Menggunakan scope `inactive()` jika ada, atau where()
        $suppliers = Supplier::where('is_active', false)->latest()->paginate(10);
        return view('suppliers.inactive', compact('suppliers'));
    }

    /**
     * Mengubah status aktif/nonaktif supplier.
     */
    public function toggleStatus(Supplier $supplier)
    {
        $supplier->is_active = !$supplier->is_active;

        // Tentukan teks aksi sebelum disimpan
        $actionText = $supplier->is_active ? 'diaktifkan' : 'dinonaktifkan';

        Supplier::withoutEvents(function () use ($supplier) {
            $supplier->save();
        });

        // [TAMBAH] Logika untuk mencatat aktivitas
        ActivityLog::create([
            'user_id'     => auth()->id(),
            'description' => "Data Supplier '{$supplier->nama_supplier}' telah {$actionText}.",
            'ip_address'  => request()->ip(),
        ]);

        $message = "Data supplier berhasil {$actionText}.";
        return redirect()->back()->with('success', $message);
    }
}