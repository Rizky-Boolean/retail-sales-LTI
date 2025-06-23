<?php

namespace App\Http\Controllers;

use App\Models\Penjualan;
use App\Models\DistribusiDetail;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;
use Illuminate\Validation\ValidationException;

class PenjualanController extends Controller
{
    /**
     * Menampilkan histori penjualan untuk cabang user.
     */
    public function index()
    {
        $user = Auth::user();
        $penjualans = Penjualan::where('cabang_id', $user->cabang_id)
                                ->latest()
                                ->paginate(10);
        
        return view('penjualan.index', compact('penjualans'));
    }

    /**
     * Menampilkan form untuk membuat transaksi penjualan baru.
     */
    public function create()
    {
        $user = Auth::user();
        // Ambil hanya sparepart yang punya stok di cabang user ini
        $spareparts = $user->cabang->spareparts()->where('stok', '>', 0)->get();

        return view('penjualan.create', compact('spareparts'));
    }

    /**
     * Menyimpan transaksi penjualan baru.
     */
    public function store(Request $request)
    {
        $request->validate([
            'tanggal_penjualan' => 'required|date',
            'nama_pembeli' => 'nullable|string|max:255',
            'details' => 'required|array|min:1',
            'details.*.sparepart_id' => 'required|exists:spareparts,id',
            'details.*.qty' => 'required|integer|min:1',
        ]);

        $user = Auth::user();
        $cabang = $user->cabang;

        try {
            DB::transaction(function () use ($request, $user, $cabang) {
                // 1. Validasi stok untuk setiap item
                foreach ($request->details as $item) {
                    $stokCabang = $cabang->spareparts()->where('sparepart_id', $item['sparepart_id'])->first();
                    if (!$stokCabang || $stokCabang->pivot->stok < $item['qty']) {
                        $namaPart = $stokCabang->nama_part ?? 'Sparepart';
                        throw ValidationException::withMessages([
                            'details' => "Stok untuk {$namaPart} tidak mencukupi. Sisa stok: " . ($stokCabang->pivot->stok ?? 0)
                        ]);
                    }
                }

                $totalPenjualan = 0;
                foreach ($request->details as $item) {
                    $sparepart = $cabang->spareparts()->find($item['sparepart_id']);
                    $totalPenjualan += $item['qty'] * $sparepart->harga_jual;
                }

                // 2. Hitung PPN jika dicentang
                $ppnNominal = 0;
                $ppnDikenakan = $request->has('ppn_dikenakan');
                if ($ppnDikenakan) {
                    $ppnNominal = $totalPenjualan * 0.11;
                }
                $totalFinal = $totalPenjualan + $ppnNominal;
                
                // 3. Simpan header penjualan
                $penjualan = Penjualan::create([
                    'nomor_nota' => 'INV-' . $cabang->id . '-' . time(),
                    'tanggal_penjualan' => $request->tanggal_penjualan,
                    'user_id' => $user->id,
                    'cabang_id' => $cabang->id,
                    'nama_pembeli' => $request->nama_pembeli ?? 'Customer Retail',
                    'ppn_dikenakan' => $ppnDikenakan,
                    'total_penjualan' => $totalPenjualan,
                    'total_ppn_penjualan' => $ppnNominal,
                    'total_final' => $totalFinal,
                ]);
                
                // 4. Proses detail penjualan
                foreach ($request->details as $item) {
                    $sparepart = $cabang->spareparts()->find($item['sparepart_id']);
                    $qty = $item['qty'];
                    
                    // Ambil HPP (Harga Pokok Penjualan) dari harga kirim terakhir saat distribusi
                    $distribusiDetail = DistribusiDetail::where('sparepart_id', $sparepart->id)
                                                          ->latest('created_at')->first();
                    $hpp = $distribusiDetail->harga_kirim_satuan ?? 0;

                    $penjualan->details()->create([
                        'sparepart_id' => $sparepart->id,
                        'qty' => $qty,
                        'harga_jual_satuan' => $sparepart->harga_jual,
                        'hpp_satuan' => $hpp,
                    ]);

                    // 5. Kurangi stok di cabang
                    $cabang->spareparts()->updateExistingPivot($sparepart->id, [
                        'stok' => $sparepart->pivot->stok - $qty
                    ]);
                }
            });

            return redirect()->route('penjualan.index')->with('success', 'Transaksi penjualan berhasil disimpan!');

        } catch (ValidationException $e) {
            return redirect()->back()->withInput()->withErrors($e->errors());
        } catch (\Exception $e) {
            return redirect()->back()->withInput()->with('error', 'Terjadi kesalahan: ' . $e->getMessage());
        }
    }

    /**
     * Menampilkan detail (nota) penjualan.
     */
    public function show(Penjualan $penjualan)
    {
        // Otorisasi: pastikan user dari cabang yang sama
        if (Auth::user()->cabang_id !== $penjualan->cabang_id) {
            abort(403);
        }
        
        $penjualan->load('user', 'cabang', 'details.sparepart');
        return view('penjualan.show', compact('penjualan'));
    }

    /**
     * Menghapus transaksi (fitur pembatalan).
     */
    public function destroy(Penjualan $penjualan)
    {
        // Otorisasi
        if (Auth::user()->cabang_id !== $penjualan->cabang_id) {
            abort(403);
        }

        try {
            DB::transaction(function () use ($penjualan) {
                // Kembalikan stok yang sudah terjual
                foreach($penjualan->details as $detail) {
                    $penjualan->cabang->spareparts()->updateExistingPivot($detail->sparepart_id, [
                        'stok' => DB::raw('stok + ' . $detail->qty)
                    ]);
                }
                // Hapus transaksi
                $penjualan->delete();
            });

            return redirect()->route('penjualan.index')->with('success', 'Transaksi berhasil dibatalkan dan stok telah dikembalikan.');

        } catch (\Exception $e) {
             return redirect()->back()->with('error', 'Gagal membatalkan transaksi: ' . $e->getMessage());
        }
    }
}
