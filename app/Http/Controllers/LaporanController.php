<?php

namespace App\Http\Controllers;

use App\Models\Sparepart;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use App\Models\Penjualan;
use Carbon\Carbon;
use App\Models\StokMasuk;

class LaporanController extends Controller
{
    /**
     * Menampilkan halaman utama (hub) untuk laporan Admin Gudang Induk.
     */
    public function indexInduk()
    {
        return view('laporan.induk.index');
    }

    /**
     * Menampilkan laporan stok di Gudang Induk.
     */
    public function stokInduk()
    {
        // Ambil semua sparepart beserta stoknya, diurutkan berdasarkan nama
        $spareparts = Sparepart::orderBy('nama_part')->get();

        return view('laporan.induk.stok', compact('spareparts'));
    }

    /**
     * [BARU] Menampilkan halaman utama (hub) untuk laporan Admin Gudang Cabang.
     */
    public function indexCabang()
    {
        return view('laporan.cabang.index');
    }

    /**
     * [BARU] Menampilkan laporan keuntungan kotor dengan filter tanggal.
     */
    public function laporanKeuntungan(Request $request)
    {
        // Tentukan rentang tanggal default (bulan ini)
        $tanggalAwal = $request->input('tanggal_awal', Carbon::now()->startOfMonth()->toDateString());
        $tanggalAkhir = $request->input('tanggal_akhir', Carbon::now()->endOfMonth()->toDateString());

        $user = Auth::user();

        // Ambil data penjualan berdasarkan filter
        $penjualans = Penjualan::where('cabang_id', $user->cabang_id)
            ->whereBetween('tanggal_penjualan', [$tanggalAwal, $tanggalAkhir])
            ->with('details.sparepart') // Eager load untuk efisiensi
            ->latest()
            ->get();

        $totalKeuntungan = 0;
        foreach ($penjualans as $penjualan) {
            foreach ($penjualan->details as $detail) {
                $keuntunganPerItem = ($detail->harga_jual_satuan - $detail->hpp_satuan) * $detail->qty;
                $totalKeuntungan += $keuntunganPerItem;
            }
        }
        return view('laporan.cabang.keuntungan', compact(
            'penjualans', 'totalKeuntungan', 'tanggalAwal', 'tanggalAkhir'
        ));
    }

    public function laporanCashflow(Request $request)
    {
        // Tentukan rentang tanggal default (bulan ini)
        $tanggalAwal = $request->input('tanggal_awal', Carbon::now()->startOfMonth()->toDateString());
        $tanggalAkhir = $request->input('tanggal_akhir', Carbon::now()->endOfMonth()->toDateString());

        $user = Auth::user();

        // 1. Ambil data Pemasukan (dari Penjualan)
        $pemasukan = Penjualan::where('cabang_id', $user->cabang_id)
            ->whereBetween('tanggal_penjualan', [$tanggalAwal, $tanggalAkhir])
            ->get();
        $totalPemasukan = $pemasukan->sum('total_final');

        // 2. Ambil data Pengeluaran (dari Distribusi yang Diterima)
        $pengeluaran = \App\Models\Distribusi::where('cabang_id_tujuan', $user->cabang_id)
            ->where('status', 'diterima')
            ->whereBetween('updated_at', [$tanggalAwal, $tanggalAkhir])
            ->get();
        $totalPengeluaran = $pengeluaran->sum('total_harga_kirim');

        // 3. Hitung Arus Kas Bersih
        $arusKasBersih = $totalPemasukan - $totalPengeluaran;

        return view('laporan.cabang.cashflow', compact(
            'pemasukan', 'pengeluaran', 'totalPemasukan', 'totalPengeluaran',
            'arusKasBersih', 'tanggalAwal', 'tanggalAkhir'
        ));
    }
    public function rekapPengeluaran(Request $request)
    {
        // Tentukan rentang tanggal default (bulan ini)
        $tanggalAwal = $request->input('tanggal_awal', Carbon::now()->startOfMonth()->toDateString());
        $tanggalAkhir = $request->input('tanggal_akhir', Carbon::now()->endOfMonth()->toDateString());

        // Ambil data Stok Masuk berdasarkan rentang tanggal
        $pengeluarans = StokMasuk::whereBetween('tanggal_masuk', [$tanggalAwal, $tanggalAkhir])
                                 ->with('supplier') // Eager load data supplier
                                 ->latest()
                                 ->get();
        
        // Hitung total pengeluaran dari data yang difilter
        $totalPengeluaran = $pengeluarans->sum('total_final');

        return view('laporan.induk.pengeluaran', compact(
            'pengeluarans',
            'totalPengeluaran',
            'tanggalAwal',
            'tanggalAkhir'
        ));
    }
}
