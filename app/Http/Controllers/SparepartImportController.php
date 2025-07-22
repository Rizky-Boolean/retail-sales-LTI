<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Maatwebsite\Excel\Facades\Excel; // <-- Import fasad Excel
use App\Imports\SparepartsImport; // <-- Import class yang baru kita buat
use App\Http\Controllers\Controller;
use Maatwebsite\Excel\Validators\ValidationException;


class SparepartImportController extends Controller
{
    /**
     * Menampilkan halaman form untuk import.
     */
    public function show()
    {
        return view('spareparts.import');
    }

    /**
     * Menyediakan file template Excel untuk diunduh.
     */
    public function downloadTemplate()
    {
        // Path ke file template di folder public
        $filePath = public_path('templates/template_import_sparepart.xlsx');

        // Pastikan file ada
        if (!file_exists($filePath)) {
            abort(404, 'File template tidak ditemukan.');
        }

        return response()->download($filePath);
    }

    /**
     * Memproses file yang diunggah dan menyimpannya.
     * (Akan kita lengkapi nanti)
     */
    public function store(Request $request)
    {
        // 1. Validasi file yang diunggah
        $request->validate([
            'file_import' => 'required|mimes:xlsx,xls'
        ]);

        try {
            // 2. Jalankan proses import
            Excel::import(new SparepartsImport, $request->file('file_import'));

            // 3. Jika berhasil, kembalikan dengan pesan sukses
            return redirect()->route('spareparts.index')->with('success', 'Data sparepart berhasil diimpor!');

        } catch (ValidationException $e) {
            // 4. Jika terjadi error validasi di dalam file Excel
            $failures = $e->failures();
            $errorMessages = [];

            foreach ($failures as $failure) {
                // Kumpulkan semua pesan error dari setiap baris yang gagal
                $errorMessages[] = 'Baris ' . $failure->row() . ': ' . implode(', ', $failure->errors());
            }

            // Kembalikan ke halaman import dengan daftar error
            return redirect()->route('spareparts.import.show')->with('import_errors', $errorMessages);
        }
    }
}
