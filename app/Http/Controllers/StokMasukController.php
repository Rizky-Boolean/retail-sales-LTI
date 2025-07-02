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
        $stokMasuks = StokMasuk::with('supplier')->latest()->paginate(10);
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
        $validated = $request->validate([
            'tanggal_masuk' => 'required|date',
            'supplier_id' => 'required|exists:suppliers,id',
            'details' => 'required|array|min:1',
            'details.*.sparepart_id' => 'required|exists:spareparts,id',
            'details.*.qty' => 'required|integer|min:1',
            'details.*.harga_beli_satuan' => 'required|numeric|min:0',
            'catatan' => 'nullable|string',
        ]);

        try {
            DB::transaction(function () use ($validated) {
                // ... (Kode untuk menghitung total & PPN masih sama)
                $totalPembelian = 0;
                foreach ($validated['details'] as $detail) {
                    $totalPembelian += $detail['qty'] * $detail['harga_beli_satuan'];
                }
                $ppnNominal = 0;
                $ppnDikenakan = $totalPembelian > 100000;
                if ($ppnDikenakan) {
                    $ppnNominal = $totalPembelian * 0.11;
                }

                // ... (Kode untuk create StokMasuk header masih sama)
                $stokMasuk = StokMasuk::create([
                    'tanggal_masuk' => $validated['tanggal_masuk'], 'supplier_id' => $validated['supplier_id'], 'user_id' => auth()->id(),
                    'total_pembelian' => $totalPembelian, 'total_ppn_supplier' => $ppnNominal, 'total_final' => $totalPembelian + $ppnNominal,
                    'catatan' => $validated['catatan'],
                ]);

                // [START] INI BAGIAN UTAMA YANG BERUBAH
                foreach ($validated['details'] as $detail) {
                    $sparepart = Sparepart::find($detail['sparepart_id']);
                    $hargaBeli = $detail['harga_beli_satuan'];
                    
                    // Hitung Harga Modal
                    $hargaModal = $ppnDikenakan ? $hargaBeli * 1.11 : $hargaBeli;

                    // Hitung Harga Jual Baru berdasarkan Markup
                    $markupPersen = $sparepart->markup_persen ?? 0;
                    $markupAmount = $hargaBeli * ($markupPersen / 100);
                    $newHargaJual = $hargaBeli + $markupAmount;

                    // Simpan detail transaksi
                    $stokMasuk->details()->create([
                        'sparepart_id' => $detail['sparepart_id'], 'qty' => $detail['qty'],
                        'harga_beli_satuan' => $hargaBeli, 'harga_modal_satuan' => $hargaModal,
                    ]);

                    // Update Master Sparepart dengan semua data baru
                    $sparepart->update([
                        'stok_induk' => $sparepart->stok_induk + $detail['qty'],
                        'harga_beli_terakhir' => $hargaBeli,
                        'harga_modal_terakhir' => $hargaModal,
                        'harga_jual' => $newHargaJual, // Harga jual di-update otomatis!
                    ]);
                }
                // [END] BAGIAN UTAMA YANG BERUBAH
            });
            return redirect()->route('stok-masuk.index')->with('success', 'Data stok masuk berhasil disimpan!');
        } catch (\Exception $e) {
            return redirect()->back()->withInput()->with('error', 'Terjadi kesalahan saat menyimpan data: ' . $e->getMessage());
        }
    }

}