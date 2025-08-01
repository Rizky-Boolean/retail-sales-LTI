<?php

namespace App\Http\Controllers;

use App\Models\Sparepart;
use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use Illuminate\Support\Facades\DB;
use App\Models\ActivityLog;


class SparepartController extends Controller
{
    /**
     * Menampilkan daftar semua sparepart dengan sorting dan search.
     */
    public function index(Request $request)
    {
        $query = Sparepart::active();
        
        // Handle search
        if ($request->filled('search')) {
            $searchTerm = $request->search;
            $query->where(function($q) use ($searchTerm) {
                $q->where('kode_part', 'like', "%{$searchTerm}%")
                  ->orWhere('nama_part', 'like', "%{$searchTerm}%")
                  ->orWhere('satuan', 'like', "%{$searchTerm}%");
            });
        }
        
        // Handle sorting
        $sortField = $request->get('sort', 'kode_part'); // default sort by kode_part
        $sortDirection = $request->get('direction', 'asc'); // default ascending
        
        // Validasi kolom yang bisa di-sort untuk keamanan
        $allowedSorts = ['kode_part', 'nama_part', 'satuan', 'harga_jual'];
        if (in_array($sortField, $allowedSorts)) {
            $query->orderBy($sortField, $sortDirection);
        } else {
            // Fallback ke default sorting jika kolom tidak valid
            $query->orderBy('kode_part', 'asc');
        }
        
        $spareparts = $query->paginate(10);
        
        // Append query parameters ke pagination links
        $spareparts->appends($request->query());
        
        return view('spareparts.index', compact('spareparts'));
    }

    /**
     * [DEPRECATED] Method search untuk AJAX - tidak digunakan lagi karena menggunakan server-side
     * Tetap dipertahankan untuk backward compatibility
     */
    public function search(Request $request)
    {
        $search = $request->get('search');
        
        $spareparts = Sparepart::active()
            ->where(function($query) use ($search) {
                $query->where('kode_part', 'LIKE', "%{$search}%")
                      ->orWhere('nama_part', 'LIKE', "%{$search}%")
                      ->orWhere('satuan', 'LIKE', "%{$search}%");
            })
            ->get();
            
        return response()->json($spareparts);
    }

    /**
     * Menampilkan daftar sparepart nonaktif dengan sorting dan search.
     */
    public function inactive(Request $request)
    {
        $query = Sparepart::where('is_active', false);
        
        // Handle search untuk halaman inactive
        if ($request->filled('search')) {
            $searchTerm = $request->search;
            $query->where(function($q) use ($searchTerm) {
                $q->where('kode_part', 'like', "%{$searchTerm}%")
                  ->orWhere('nama_part', 'like', "%{$searchTerm}%")
                  ->orWhere('satuan', 'like', "%{$searchTerm}%");
            });
        }
        
        // Handle sorting untuk halaman inactive
        $sortField = $request->get('sort', 'kode_part');
        $sortDirection = $request->get('direction', 'asc');
        
        $allowedSorts = ['kode_part', 'nama_part', 'satuan', 'harga_jual'];
        if (in_array($sortField, $allowedSorts)) {
            $query->orderBy($sortField, $sortDirection);
        } else {
            $query->orderBy('kode_part', 'asc');
        }
        
        $spareparts = $query->paginate(10);
        $spareparts->appends($request->query());
        
        return view('spareparts.inactive', compact('spareparts'));
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
        ]);

        // 2. Hitung harga jual berdasarkan markup default
        $markupPersen = 40; // Markup default 40%

        // 3. Simpan data baru. Harga jual defaultnya 0 karena belum ada harga beli.
        Sparepart::create($validated);

        // 4. Redirect ke halaman index dengan pesan sukses
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
     * [BARU] Mengubah status aktif/nonaktif.
     */
    public function toggleStatus(Sparepart $sparepart)
    {
        $sparepart->is_active = !$sparepart->is_active;
        $actionText = $sparepart->is_active ? 'diaktifkan' : 'dinonaktifkan';
        
        Sparepart::withoutEvents(function () use ($sparepart) {
          $sparepart->save();
        });

        // Mencatat aktivitas
        ActivityLog::create([
            'user_id'     => auth()->id(),
            'description' => "Data Sparepart '{$sparepart->nama_part}' telah {$actionText}.",
            'ip_address'  => request()->ip(),
        ]);

        $message = "Data sparepart berhasil {$actionText}.";
        return redirect()->back()->with('success', $message);
    }
}