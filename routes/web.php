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
    
    // Grup Rute HANYA untuk Admin Gudang Induk
    Route::middleware(['role:admin_induk'])->group(function () {
        
        Route::get('suppliers/trash', [App\Http\Controllers\SupplierController::class, 'trash'])->name('suppliers.trash');
        Route::patch('suppliers/{id}/restore', [App\Http\Controllers\SupplierController::class, 'restore'])->name('suppliers.restore');
        Route::delete('suppliers/{id}/force-delete', [App\Http\Controllers\SupplierController::class, 'forceDelete'])->name('suppliers.forceDelete');
        Route::resource('suppliers', SupplierController::class);
        
        Route::get('users/trash', [App\Http\Controllers\UserController::class, 'trash'])->name('users.trash');
        Route::patch('users/{id}/restore', [App\Http\Controllers\UserController::class, 'restore'])->name('users.restore');
        Route::delete('users/{id}/force-delete', [App\Http\Controllers\UserController::class, 'forceDelete'])->name('users.forceDelete');
        Route::resource('users', UserController::class);

        Route::get('cabangs/trash', [App\Http\Controllers\CabangController::class, 'trash'])->name('cabangs.trash');
        Route::patch('cabangs/{id}/restore', [App\Http\Controllers\CabangController::class, 'restore'])->name('cabangs.restore');
        Route::delete('cabangs/{id}/force-delete', [App\Http\Controllers\CabangController::class, 'forceDelete'])->name('cabangs.forceDelete');
        Route::resource('cabangs', CabangController::class);
        
        Route::get('stok-masuk', [StokMasukController::class, 'index'])->name('stok-masuk.index');
        Route::get('stok-masuk/create', [StokMasukController::class, 'create'])->name('stok-masuk.create');
        Route::post('stok-masuk', [StokMasukController::class, 'store'])->name('stok-masuk.store');
        Route::get('stok-masuk/{id}', [StokMasukController::class, 'show'])->name('stok-masuk.show');
        
        Route::get('distribusi', [DistribusiController::class, 'index'])->name('distribusi.index');
        Route::get('distribusi/create', [DistribusiController::class, 'create'])->name('distribusi.create');
        Route::post('distribusi', [DistribusiController::class, 'store'])->name('distribusi.store');

        Route::get('/laporan', [LaporanController::class, 'indexInduk'])->name('laporan.induk.index');
        Route::get('/laporan/stok-induk', [LaporanController::class, 'stokInduk'])->name('laporan.induk.stok');
        Route::get('/laporan/pengeluaran', [LaporanController::class, 'rekapPengeluaran'])->name('laporan.induk.pengeluaran');

        Route::get('/activity-logs', [App\Http\Controllers\ActivityLogController::class, 'index'])->name('activity-logs.index');
        
        Route::get('spareparts/trash', [App\Http\Controllers\SparepartController::class, 'trash'])->name('spareparts.trash');
        Route::patch('spareparts/{id}/restore', [App\Http\Controllers\SparepartController::class, 'restore'])->name('spareparts.restore');
        Route::delete('spareparts/{id}/force-delete', [App\Http\Controllers\SparepartController::class, 'forceDelete'])->name('spareparts.forceDelete');
        Route::resource('spareparts', SparepartController::class);

        Route::get('/laporan/penjualan-cabang', [App\Http\Controllers\LaporanController::class, 'laporanPenjualanSemuaCabang'])->name('laporan.induk.penjualan');
    });

    // Grup Rute HANYA untuk Admin Gudang Cabang
    Route::middleware(['role:admin_cabang'])->group(function () {
        Route::get('/stok-cabang', [CabangController::class, 'stokIndex'])->name('cabang.stok.index');
        Route::get('/penerimaan', [CabangController::class, 'penerimaanIndex'])->name('cabang.penerimaan.index');
        Route::patch('/penerimaan/{distribusi}/terima', [CabangController::class, 'terimaBarang'])->name('cabang.penerimaan.terima');
        Route::resource('penjualan', \App\Http\Controllers\PenjualanController::class)->except(['edit', 'update']);

        Route::get('/laporan-cabang', [LaporanController::class, 'indexCabang'])->name('laporan.cabang.index');
        Route::get('/laporan-cabang/keuntungan', [LaporanController::class, 'laporanKeuntungan'])->name('laporan.cabang.keuntungan');
        Route::get('/laporan-cabang/cashflow', [LaporanController::class, 'laporanCashflow'])->name('laporan.cabang.cashflow');
    });

    // Grup Rute yang bisa diakses oleh KEDUA admin
    Route::middleware(['role:admin_induk,admin_cabang'])->group(function () {
        Route::get('distribusi/{distribusi}', [DistribusiController::class, 'show'])->name('distribusi.show');
    });

    Route::post('/email/verification-notification', function (Illuminate\Http\Request $request) {
        $request->user()->sendEmailVerificationNotification();
        return back()->with('status', 'verification-link-sent');
    })->name('verification.send');
});
