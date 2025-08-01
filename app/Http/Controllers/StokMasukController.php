<?php

namespace App\Http\Controllers;

use App\Models\StokMasuk;
use App\Models\Supplier;
use App\Models\Sparepart;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use App\Models\ActivityLog;

class StokMasukController extends Controller
{
    public function index(Request $request)
    {
        // Query dasar untuk memuat relasi dan agregasi
        $query = StokMasuk::with('supplier')->withSum('details', 'qty');

        // Terapkan filter pencarian jika ada
        $search = $request->input('search');
        if ($search) {
            $query->where(function($q) use ($search) {
                // Cari berdasarkan ID (tanpa prefix "TR-")
                $q->where('id', 'like', "%{$search}%")
                  // Cari berdasarkan nama supplier melalui relasi
                  ->orWhereHas('supplier', function($subQ) use ($search) {
                      $subQ->where('nama_supplier', 'like', "%{$search}%");
                  });
            });
        }

        // Urutkan berdasarkan yang terbaru dan lakukan pagination
        $stokMasuks = $query->latest('tanggal_masuk')->paginate(10)->withQueryString();
                                        
        return view('stok-masuk.index', compact('stokMasuks'));
    }

    public function show($id)
    {
        $stokMasuk = StokMasuk::with('supplier', 'details.sparepart')->findOrFail($id);
        return view('stok-masuk.show', compact('stokMasuk'));
    }

    public function create()
    {
        $suppliers = Supplier::active()->orderBy('nama_supplier')->get();
        $spareparts = Sparepart::active()->orderBy('nama_part')->get();
        return view('stok-masuk.create', compact('suppliers', 'spareparts'));
    }

    /**
     * Menyimpan data baru dengan logika PPN opsional.
     */
    public function store(Request $request)
    {
        $request->validate([
            'tanggal_masuk' => 'required|date|before_or_equal:today',
            'supplier_id' => 'required|exists:suppliers,id',
            'details' => 'required|array|min:1',
            'details.*.sparepart_id' => 'required|exists:spareparts,id',
            'details.*.qty' => 'required|integer|min:1',
            'details.*.harga_beli_satuan' => 'required|numeric|min:1000', // Ubah dari min:0 ke min:1000
        ], [
            // Custom error messages
            'details.*.harga_beli_satuan.min' => 'Harga beli satuan minimal Rp 1.000',
            'details.*.harga_beli_satuan.required' => 'Harga beli satuan wajib diisi',
            'details.*.harga_beli_satuan.numeric' => 'Harga beli satuan harus berupa angka',
        ]);

        try {
            DB::transaction(function () use ($request) {
                // Hitung total pembelian awal
                $totalPembelian = 0;
                foreach ($request->details as $detail) {
                    $totalPembelian += $detail['qty'] * $detail['harga_beli_satuan'];
                }

                // Logika PPN bergantung pada ceklis
                $ppnDikenakan = $request->has('ppn_dikenakan');
                $ppnNominal = $ppnDikenakan ? $totalPembelian * 0.11 : 0;

                // Simpan data header
                $stokMasuk = StokMasuk::create([
                    'tanggal_masuk' => $request->tanggal_masuk,
                    'supplier_id' => $request->supplier_id,
                    'ppn_dikenakan' => $ppnDikenakan,
                    'user_id' => auth()->id(),
                    'total_pembelian' => $totalPembelian,
                    'total_ppn_supplier' => $ppnNominal,
                    'total_final' => $totalPembelian + $ppnNominal,
                    'catatan' => $request->catatan,
                ]);

                // Proses setiap detail item
                foreach ($request->details as $detail) {
                    $sparepart = Sparepart::find($detail['sparepart_id']);
                    $hargaBeli = $detail['harga_beli_satuan'];
                    
                    // Harga modal bergantung pada status PPN
                    $hargaModal = $ppnDikenakan ? $hargaBeli * 1.11 : $hargaBeli;
                    
                    // Logika update harga jual (jika masih menggunakan markup)
                    $newHargaJual = $hargaBeli + ($hargaBeli * ($sparepart->markup_persen / 100));

                    // Simpan detail transaksi
                    $stokMasuk->details()->create([
                        'sparepart_id' => $detail['sparepart_id'],
                        'qty' => $detail['qty'],
                        'harga_beli_satuan' => $hargaBeli,
                        'harga_modal_satuan' => $hargaModal,
                    ]);

                    // Update master sparepart
                    $sparepart->update([
                        'stok_induk' => $sparepart->stok_induk + $detail['qty'],
                        'harga_beli_terakhir' => $hargaBeli,
                        'harga_modal_terakhir' => $hargaModal,
                        'harga_jual' => $newHargaJual,
                    ]);
                }
            });

            return redirect()->route('stok-masuk.index')->with('success', 'Data stok masuk berhasil disimpan!');
        } catch (\Exception $e) {
            return redirect()->back()->withInput()->with('error', 'Terjadi kesalahan saat menyimpan data: ' . $e->getMessage());
        }
    }
}
