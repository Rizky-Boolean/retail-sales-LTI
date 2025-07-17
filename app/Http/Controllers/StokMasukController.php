<?php

namespace App\Http\Controllers;

use App\Models\Sparepart;
use App\Models\StokMasuk;
use App\Models\StokMasukDetail;
use App\Models\Supplier;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB; // <-- Penting untuk Database Transaction

class StokMasukController extends Controller
{
    // Menampilkan histori stok masuk
    public function index()
    {
        $stokMasuks = StokMasuk::with('supplier')
                                ->withSum('details', 'qty')
                                ->latest()
                                ->paginate(10);
                                
        return view('stok-masuk.index', compact('stokMasuks'));
    }


    // Menampilkan detail satu transaksi stok masuk
    public function show($id)
    {
        $stokMasuk = StokMasuk::with('supplier', 'details.sparepart')->findOrFail($id);
        return view('stok-masuk.show', compact('stokMasuk'));
    }

    // Menampilkan form untuk membuat data baru
    public function create()
    {
        $suppliers = Supplier::orderBy('nama_supplier')->get();
        $spareparts = Sparepart::orderBy('nama_part')->get();
        return view('stok-masuk.create', compact('suppliers', 'spareparts'));
    }

    // Menyimpan data baru
        // GANTI SELURUH METHOD STORE ANDA DENGAN INI
    public function store(Request $request)
    {
        $request->validate([
            'tanggal_masuk' => 'required|date',
            'supplier_id' => 'required|exists:suppliers,id',
            'details' => 'required|array|min:1',
            'details.*.sparepart_id' => 'required|exists:spareparts,id',
            'details.*.qty' => 'required|integer|min:1',
            'details.*.harga_beli_satuan' => 'required|numeric|min:0',
        ]);

        try {
            DB::transaction(function () use ($request) {
                // Hitung total pembelian awal
                $totalPembelian = 0;
                foreach ($request->details as $detail) {
                    $totalPembelian += $detail['qty'] * $detail['harga_beli_satuan'];
                }

                $ppnDikenakan = $request->has('ppn_dikenakan');
                $ppnNominal = $ppnDikenakan ? $totalPembelian * 0.11 : 0;

                // Simpan data header
                $stokMasuk = StokMasuk::create([
                    'tanggal_masuk' => $request->tanggal_masuk,
                    'supplier_id' => $request->supplier_id,
                    'ppn_dikenakan' => $ppnDikenakan, // Simpan status ceklis
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
                    
                    // [UBAH] Harga modal sekarang bergantung pada status PPN
                    $hargaModal = $ppnDikenakan ? $hargaBeli * 1.11 : $hargaBeli;
                    
                    // ... (logika update harga jual tetap sama)

                    $stokMasuk->details()->create([ /* ... data detail ... */ ]);
                    $sparepart->update([ /* ... update sparepart ... */ ]);
                }
            });
            return redirect()->route('stok-masuk.index')->with('success', 'Data stok masuk berhasil disimpan!');
        } catch (\Exception $e) {
            return redirect()->back()->withInput()->with('error', 'Terjadi kesalahan saat menyimpan data: ' . $e->getMessage());
        }
    }

}