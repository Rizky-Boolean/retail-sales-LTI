<?php

namespace App\Imports;

use App\Models\Sparepart;
use Maatwebsite\Excel\Concerns\ToModel;
use Maatwebsite\Excel\Concerns\WithHeadingRow;
use Maatwebsite\Excel\Concerns\WithValidation;

class SparepartsImport implements ToModel, WithHeadingRow, WithValidation
{
    /**
    * @param array $row
    *
    * @return \Illuminate\Database\Eloquent\Model|null
    */
    public function model(array $row)
    {
        return new Sparepart([
            'kode_part'     => $row['kode_part'],
            'nama_part'     => $row['nama_part'],
            'satuan'        => $row['satuan'],
            'markup_persen' => 40, // [UBAH] Markup profit default diatur di sini
            // Harga jual tidak diisi saat import, akan default ke 0
        ]);
    }

    /**
     * Tentukan aturan validasi untuk setiap baris.
     */
    public function rules(): array
    {
        return [
            'kode_part' => 'required|unique:spareparts,kode_part',
            'nama_part' => 'required|string',
            'satuan' => 'required|string',
        ];
    }

    /**
     * Pesan error kustom untuk validasi.
     */
    public function customValidationMessages()
    {
        return [
            'kode_part.required' => 'Kolom kode_part wajib diisi pada baris :row.',
            'kode_part.unique' => 'Kode part :value sudah ada di database pada baris :row.',
            'nama_part.required' => 'Kolom nama_part wajib diisi pada baris :row.',
            'satuan.required' => 'Kolom satuan wajib diisi pada baris :row.',
        ];
    }
}