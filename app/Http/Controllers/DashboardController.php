<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Carbon\Carbon;
use App\Models\Sparepart;
use App\Models\Supplier;
use App\Models\Cabang;
use App\Models\Distribusi;
use App\Models\Penjualan;
use App\Models\StokMasuk;
use Illuminate\Support\Facades\DB;

class DashboardController extends Controller
{
    /**
     * Menampilkan halaman dashboard yang sesuai dengan peran pengguna.
     */
    public function index()
    {
        $user = Auth::user();
        $viewData = [];

        if ($user->role === 'super_admin') {
            $viewData = $this->getDataForSuperAdmin();
        } elseif ($user->role === 'admin_gudang_induk') {
            $viewData = $this->getDataForAdminGudangInduk();
        } elseif ($user->role === 'admin_gudang_cabang') {
            $viewData = $this->getDataForAdminGudangCabang($user);
        }

        return view('dashboard', $viewData);
    }

    /**
     * Mengambil data untuk dashboard Super Admin.
     */
    private function getDataForSuperAdmin()
    {
        // --- Statistik Kartu ---
        $totalStokInduk = Sparepart::sum('stok_induk');
        $totalStokCabang = DB::table('cabang_sparepart')->sum('stok');

        // --- Data untuk Grafik ---
        // 1. Grafik Pengeluaran (6 bulan terakhir)
        $pengeluaranData = StokMasuk::select(
            DB::raw("DATE_FORMAT(tanggal_masuk, '%b') as bulan"),
            DB::raw("SUM(total_final) as total")
        )
        ->where('tanggal_masuk', '>=', Carbon::now()->subMonths(5)->startOfMonth())
        ->groupBy('bulan')
        ->orderByRaw("MIN(tanggal_masuk)")
        ->get();

        // 2. Grafik Keuntungan (6 bulan terakhir)
        $keuntunganData = Penjualan::join('penjualan_details', 'penjualans.id', '=', 'penjualan_details.penjualan_id')
            ->select(
                DB::raw("DATE_FORMAT(tanggal_penjualan, '%b') as bulan"),
                DB::raw("SUM((penjualan_details.harga_jual_satuan - penjualan_details.hpp_satuan) * penjualan_details.qty) as total_profit")
            )
            ->where('tanggal_penjualan', '>=', Carbon::now()->subMonths(5)->startOfMonth())
            ->groupBy('bulan')
            ->orderByRaw("MIN(tanggal_penjualan)")
            ->get();

        // 3. Grafik Distribusi Stok per Cabang
        $distribusiStokData = Cabang::query()
            ->select('nama_cabang')
            ->addSelect(['total_stok' => 
                DB::table('cabang_sparepart')
                    ->selectRaw('ifnull(sum(stok), 0)')
                    ->whereColumn('cabang_id', 'cabangs.id')
            ])
            ->get();

        return [
            'totalSparepart' => Sparepart::count(),
            'totalSupplier' => Supplier::count(),
            'totalCabang' => Cabang::count(),
            'totalStokSeluruhGudang' => $totalStokInduk + $totalStokCabang,
            // [UBAH] Logika untuk stok hampir habis diperbaiki
            'stokHampirHabis' => Sparepart::where('stok_induk', '>', 0)
                                          ->where('stok_induk', '<', 10)
                                          ->orderBy('stok_induk', 'asc')
                                          ->take(5)
                                          ->get(),
            'distribusiTerbaru' => Distribusi::with('cabangTujuan')->latest()->take(5)->get(),
            'chartPengeluaran' => $pengeluaranData,
            'chartKeuntungan' => $keuntunganData,
            'chartDistribusiStok' => $distribusiStokData,
        ];
    }
    
    /**
     * Mengambil data untuk dashboard Admin Gudang Induk.
     */
    private function getDataForAdminGudangInduk()
    {
        return [
            'distribusiTerbaru' => Distribusi::with('cabangTujuan')->latest()->take(10)->get(),
        ];
    }

    /**
     * Mengambil data untuk dashboard Admin Gudang Cabang.
     */
    private function getDataForAdminGudangCabang($user)
    {
        $cabangId = $user->cabang_id;

        // Penjualan hari ini
        $penjualanHariIni = Penjualan::where('cabang_id', $cabangId)
                                     ->whereDate('tanggal_penjualan', Carbon::today())
                                     ->sum('total_final');

        // Keuntungan bulan ini
        $penjualanBulanIni = Penjualan::where('cabang_id', $cabangId)
                                      ->whereMonth('tanggal_penjualan', Carbon::now()->month)
                                      ->whereYear('tanggal_penjualan', Carbon::now()->year)
                                      ->with('details')
                                      ->get();
        
        $keuntunganBulanIni = $penjualanBulanIni->reduce(function ($carry, $penjualan) {
            $labaPerNota = $penjualan->details->sum(function ($detail) { return ($detail->harga_jual_satuan - $detail->hpp_satuan) * $detail->qty; });
            return $carry + $labaPerNota;
        }, 0);

        // [UBAH] Logika untuk stok hampir habis di cabang juga diperbaiki
        $stokHampirHabisCabang = DB::table('cabang_sparepart')
            ->join('spareparts', 'cabang_sparepart.sparepart_id', '=', 'spareparts.id')
            ->select('spareparts.nama_part', 'cabang_sparepart.stok')
            ->where('cabang_sparepart.cabang_id', $cabangId)
            ->where('cabang_sparepart.stok', '>', 0)
            ->where('cabang_sparepart.stok', '<', 10)
            ->orderBy('cabang_sparepart.stok', 'asc')
            ->take(5)
            ->get();

        $penjualan7Hari = Penjualan::select(DB::raw("DATE(tanggal_penjualan) as tanggal"), DB::raw("SUM(total_final) as total"))->where('cabang_id', $cabangId)->where('tanggal_penjualan', '>=', Carbon::now()->subDays(6))->groupBy('tanggal')->orderBy('tanggal', 'asc')->get();
        $sparepartTerlaris = DB::table('penjualan_details')->join('penjualans', 'penjualan_details.penjualan_id', '=', 'penjualans.id')->join('spareparts', 'penjualan_details.sparepart_id', '=', 'spareparts.id')->select('spareparts.nama_part', DB::raw('SUM(penjualan_details.qty) as total_terjual'))->where('penjualans.cabang_id', $cabangId)->whereMonth('penjualans.tanggal_penjualan', Carbon::now()->month)->whereYear('penjualans.tanggal_penjualan', Carbon::now()->year)->groupBy('spareparts.nama_part')->orderBy('total_terjual', 'desc')->take(5)->get();

        return [
            'penjualanHariIni' => $penjualanHariIni,
            'keuntunganBulanIni' => $keuntunganBulanIni,
            'kirimanMenunggu' => Distribusi::where('cabang_id_tujuan', $cabangId)->where('status', 'dikirim')->count(),
            'stokHampirHabis' => $stokHampirHabisCabang,
            'penjualanTerbaru' => Penjualan::where('cabang_id', $cabangId)->latest()->take(5)->get(),
            'chartPenjualan7Hari' => $penjualan7Hari,
            'sparepartTerlaris' => $sparepartTerlaris,
        ];
    }
}