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
use Illuminate\Support\Facades\DB;
use App\Models\StokMasuk;

class DashboardController extends Controller
{
    /**
     * Menampilkan halaman dashboard yang sesuai dengan peran pengguna.
     */
    public function index()
    {
        $user = Auth::user();
        $viewData = [];

        if ($user->role === 'admin_induk') {
            $viewData = $this->getDataForAdminInduk();
        } elseif ($user->role === 'admin_cabang') {
            $viewData = $this->getDataForAdminCabang($user);
        }

        return view('dashboard', $viewData);
    }

    /**
     * Mengambil data untuk dashboard Admin Gudang Induk.
     */
    private function getDataForAdminInduk()
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

        // [FIX] 3. Grafik Distribusi Stok per Cabang
        // Menggunakan subquery untuk menjumlahkan stok dari tabel pivot dengan benar.
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
            'stokHampirHabis' => Sparepart::where('stok_induk', '<', 10)->orderBy('stok_induk', 'asc')->take(5)->get(),
            'distribusiTerbaru' => Distribusi::with('cabangTujuan')->latest()->take(5)->get(),
            'chartPengeluaran' => $pengeluaranData,
            'chartKeuntungan' => $keuntunganData,
            'chartDistribusiStok' => $distribusiStokData,
        ];
    }

    /**
     * Mengambil data untuk dashboard Admin Gudang Cabang.
     */
    private function getDataForAdminCabang($user)
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
            $labaPerNota = $penjualan->details->sum(function ($detail) {
                return ($detail->harga_jual_satuan - $detail->hpp_satuan) * $detail->qty;
            });
            return $carry + $labaPerNota;
        }, 0);

        return [
            'penjualanHariIni' => $penjualanHariIni,
            'keuntunganBulanIni' => $keuntunganBulanIni,
            'kirimanMenunggu' => Distribusi::where('cabang_id_tujuan', $cabangId)->where('status', 'dikirim')->count(),
            'stokHampirHabis' => $user->cabang->spareparts()->where('stok', '<', 10)->orderBy('stok', 'asc')->take(5)->get(),
            'penjualanTerbaru' => Penjualan::where('cabang_id', $cabangId)->latest()->take(5)->get(),
        ];
    }
}