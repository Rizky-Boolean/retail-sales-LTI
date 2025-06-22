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

class DashboardController extends Controller
{
    /**
     * Menampilkan halaman dashboard yang sesuai dengan peran pengguna.
     */
    public function index()
    {
        $user = Auth::user();
        
        // Selalu siapkan array kosong untuk data
        $viewData = [];

        // Logika untuk Admin Gudang Induk
        if ($user->role === 'admin_induk') {
            $viewData = [
                'totalSparepart' => Sparepart::count(),
                'totalSupplier' => Supplier::count(),
                'totalCabang' => Cabang::count(),
                'stokHampirHabis' => Sparepart::where('stok_induk', '<', 10)->orderBy('stok_induk', 'asc')->take(5)->get(),
                'distribusiTerbaru' => Distribusi::with('cabangTujuan')->latest()->take(5)->get(),
            ];
        } 
        // Logika untuk Admin Gudang Cabang
        elseif ($user->role === 'admin_cabang') {
            $cabangId = $user->cabang_id;
            
            $penjualanHariIni = Penjualan::where('cabang_id', $cabangId)->whereDate('tanggal_penjualan', Carbon::today())->sum('total_final');

            $penjualanBulanIni = Penjualan::where('cabang_id', $cabangId)->whereMonth('tanggal_penjualan', Carbon::now()->month)->whereYear('tanggal_penjualan', Carbon::now()->year)->with('details')->get();
            $keuntunganBulanIni = $penjualanBulanIni->reduce(function ($carry, $penjualan) {
                $labaPerNota = $penjualan->details->sum(function ($detail) {
                    return ($detail->harga_jual_satuan - $detail->hpp_satuan) * $detail->qty;
                });
                return $carry + $labaPerNota;
            }, 0);

            $viewData = [
                'penjualanHariIni' => $penjualanHariIni,
                'keuntunganBulanIni' => $keuntunganBulanIni,
                'kirimanMenunggu' => Distribusi::where('cabang_id_tujuan', $cabangId)->where('status', 'dikirim')->count(),
                'stokHampirHabis' => $user->cabang->spareparts()->where('stok', '<', 10)->orderBy('stok', 'asc')->take(5)->get(),
                'penjualanTerbaru' => Penjualan::where('cabang_id', $cabangId)->latest()->take(5)->get(),
            ];
        }

        // Selalu kirim $viewData, meskipun kosong.
        // View 'dashboard.blade.php' sudah aman untuk menangani ini.
        return view('dashboard', $viewData);
    }
}
