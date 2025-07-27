<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\ProfileController;
use App\Http\Controllers\SparepartController;
use App\Http\Controllers\SupplierController;
use App\Http\Controllers\StokMasukController;
use App\Http\Controllers\DistribusiController;
use App\Http\Controllers\CabangController;
use App\Http\Controllers\UserController;
use App\Http\Controllers\LaporanController;
use App\Http\Controllers\DashboardController;
use App\Http\Controllers\Auth\AuthenticatedSessionController;
use App\Http\Controllers\Auth\PasswordController;
use App\Http\Controllers\NotificationController;
use App\Http\Controllers\ActivityLogController;
use App\Http\Controllers\PenjualanController;


/*
|--------------------------------------------------------------------------
| Web Routes
|--------------------------------------------------------------------------
*/

Route::get('/', function () {
    return redirect()->route('login');
});

Route::middleware('guest')->group(function () {
    // Hanya izinkan rute untuk login
    Route::get('login', [AuthenticatedSessionController::class, 'create'])->name('login');
    Route::post('login', [AuthenticatedSessionController::class, 'store']);
});

Route::middleware('auth')->group(function () {
    // Rute untuk update password (di halaman profil) dan logout
    Route::put('password', [PasswordController::class, 'update'])->name('password.update');
    Route::post('logout', [AuthenticatedSessionController::class, 'destroy'])->name('logout');
    Route::get('notifications/{id}/mark-as-read', [App\Http\Controllers\NotificationController::class, 'markAsRead'])->name('notifications.markAsRead');
    
});

// Grup route untuk semua user yang sudah login
Route::middleware('auth')->group(function () {
    Route::get('/dashboard', [DashboardController::class, 'index'])->name('dashboard');
    Route::get('/profile', [ProfileController::class, 'edit'])->name('profile.edit');
    Route::patch('/profile', [ProfileController::class, 'update'])->name('profile.update');
    Route::delete('/profile', [ProfileController::class, 'destroy'])->name('profile.destroy');

    // Grup Rute HANYA untuk Super Admin
    Route::middleware(['role:super_admin'])->group(function () {


        Route::get('spareparts/inactive', [SparepartController::class, 'inactive'])->name('spareparts.inactive');
        Route::patch('spareparts/{sparepart}/toggle-status', [SparepartController::class, 'toggleStatus'])->name('spareparts.toggleStatus');
        
        // Rute untuk Fitur Import Sparepart
        Route::get('/spareparts/import', [App\Http\Controllers\SparepartImportController::class, 'show'])->name('spareparts.import.show');
        Route::post('/spareparts/import', [App\Http\Controllers\SparepartImportController::class, 'store'])->name('spareparts.import.store');
        Route::get('/spareparts/import/template', [App\Http\Controllers\SparepartImportController::class, 'downloadTemplate'])->name('spareparts.import.template');
        Route::get('/spareparts/search', [SparepartController::class, 'search'])->name('spareparts.search');

        Route::resource('spareparts', SparepartController::class);

        Route::get('/suppliers/search', [SupplierController::class, 'search'])->name('suppliers.search');
        Route::get('suppliers/inactive', [SupplierController::class, 'inactive'])->name('suppliers.inactive');
        Route::patch('suppliers/{supplier}/toggle-status', [SupplierController::class, 'toggleStatus'])->name('suppliers.toggleStatus');
        
        Route::resource('suppliers', SupplierController::class);

        Route::get('users/inactive', [UserController::class, 'inactive'])->name('users.inactive');
        Route::patch('users/{user}/toggle-status', [UserController::class, 'toggleStatus'])->name('users.toggleStatus');
        Route::resource('users', UserController::class);

        Route::get('cabangs/inactive', [CabangController::class, 'inactive'])->name('cabangs.inactive');
        Route::patch('cabangs/{cabang}/toggle-status', [CabangController::class, 'toggleStatus'])->name('cabangs.toggleStatus');
        Route::resource('cabangs', CabangController::class);

        // Rute Transaksi & Laporan Super Admin
        Route::get('/stok-masuk/search', [StokMasukController::class, 'search'])->name('stok-masuk.search');
        Route::get('/stok-masuk/create', [StokMasukController::class, 'create'])->name('stok-masuk.create');
        Route::post('/stok-masuk', [StokMasukController::class, 'store'])->name('stok-masuk.store');
        Route::get('/stok-masuk/{id}', [StokMasukController::class, 'show'])->where('id', '[0-9]+')->name('stok-masuk.show');
        Route::get('/stok-masuk', [StokMasukController::class, 'index'])->name('stok-masuk.index');

        Route::get('/laporan-induk', [LaporanController::class, 'indexInduk'])->name('laporan.induk.index');
        Route::get('/laporan-induk/stok', [LaporanController::class, 'stokInduk'])->name('laporan.induk.stok');
        Route::get('/laporan-induk/pengeluaran', [LaporanController::class, 'rekapPengeluaran'])->name('laporan.induk.pengeluaran');
        Route::get('/laporan-induk/penjualan-cabang', [LaporanController::class, 'laporanPenjualanSemuaCabang'])->name('laporan.induk.penjualan');

        Route::get('/activity-logs', [ActivityLogController::class, 'index'])->name('activity-logs.index');
        Route::get('/activity-logs/search', [ActivityLogController::class, 'search'])->name('activity-logs.search');

    });

    // Grup Rute untuk Admin Gudang Induk
    Route::middleware(['role:admin_gudang_induk'])->group(function () {
    Route::get('/stok-gudang-induk', [App\Http\Controllers\DistribusiController::class, 'stokInduk'])->name('distribusi.stok.induk');
    Route::get('/stok-gudang-induk/search', [DistribusiController::class, 'searchStokInduk'])->name('distribusi.stok.search');

    });

    // Grup Rute untuk pengelola gudang induk
    Route::middleware(['role:super_admin,admin_gudang_induk'])->group(function () {
        Route::get('distribusi', [DistribusiController::class, 'index'])->name('distribusi.index');
        Route::get('distribusi/create', [DistribusiController::class, 'create'])->name('distribusi.create');
        Route::get('/distribusi/search', [DistribusiController::class, 'search'])->name('distribusi.search');
        Route::post('distribusi', [DistribusiController::class, 'store'])->name('distribusi.store');
        Route::resource('distribusi', DistribusiController::class);
    });

    // Grup Rute HANYA untuk Admin Gudang Cabang
    Route::middleware(['role:admin_gudang_cabang'])->group(function () {
        Route::get('/stok-cabang', [CabangController::class, 'stokIndex'])->name('cabang.stok.index');
        Route::get('/stok-cabang/search', [CabangController::class, 'searchStok'])->name('cabang.stok.search');


        Route::get('/penerimaan', [CabangController::class, 'penerimaanIndex'])->name('cabang.penerimaan.index');
        Route::patch('/penerimaan/{distribusi}/terima', [CabangController::class, 'terimaBarang'])->name('cabang.penerimaan.terima');
        Route::patch('/penerimaan/{distribusi}/tolak', [App\Http\Controllers\CabangController::class, 'tolakBarang'])->name('cabang.penerimaan.tolak');
        Route::get('/penerimaan/search', [CabangController::class, 'searchPenerimaan'])->name('cabang.penerimaan.search');

        Route::get('/penjualan/search', [PenjualanController::class, 'search'])->name('penjualan.search');
        Route::resource('penjualan', \App\Http\Controllers\PenjualanController::class)->except(['edit', 'update']);

        Route::get('/laporan-cabang', [LaporanController::class, 'indexCabang'])->name('laporan.cabang.index');
        Route::get('/laporan-cabang/keuntungan', [LaporanController::class, 'laporanKeuntungan'])->name('laporan.cabang.keuntungan');
        Route::get('/laporan-cabang/cashflow', [LaporanController::class, 'laporanCashflow'])->name('laporan.cabang.cashflow');

    });

    // Grup Rute BERSAMA
    Route::middleware(['role:super_admin,admin_gudang_induk,admin_gudang_cabang'])->group(function () {
        Route::get('distribusi/{distribusi}', [DistribusiController::class, 'show'])->name('distribusi.show');
        Route::get('notifications/{id}/mark-as-read', [NotificationController::class, 'markAsRead'])->name('notifications.markAsRead');
    });

    Route::post('/email/verification-notification', function (Illuminate\Http\Request $request) {
        $request->user()->sendEmailVerificationNotification();
        return back()->with('status', 'verification-link-sent');
    })->name('verification.send');
});
