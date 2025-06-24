<?php

namespace App\Traits;

use App\Models\ActivityLog;
use Illuminate\Support\Facades\Auth;

trait LogsActivity
{
    /**
     * Boot the trait.
     * Secara otomatis mendaftarkan event listeners saat model di-boot.
     */
    protected static function bootLogsActivity()
    {
        // Event listener untuk saat data BARU DIBUAT
        static::created(function ($model) {
            static::logActivity($model, 'dibuat');
        });

        // Event listener untuk saat data DIUBAH
        static::updated(function ($model) {
            static::logActivity($model, 'diperbarui');
        });

        // Event listener untuk saat data DIHAPUS
        static::deleted(function ($model) {
            static::logActivity($model, 'dihapus');
        });
    }

    /**
     * Mencatat aktivitas ke database.
     */
    protected static function logActivity($model, $action)
    {
        ActivityLog::create([
            'user_id' => Auth::id(),
            'description' => static::getActivityDescription($model, $action),
            'ip_address' => request()->ip(),
            'user_agent' => request()->header('User-Agent'),
        ]);
    }

    /**
     * Membuat teks deskripsi untuk log.
     */
    protected static function getActivityDescription($model, $action): string
    {
        $modelName = class_basename($model); // Mendapatkan nama model, e.g., "Sparepart"
        $identifier = $model->name ?? $model->nama_part ?? $model->nama_supplier ?? $model->nama_cabang ?? $model->nomor_nota ?? "ID: {$model->id}";

        return "Data {$modelName} '{$identifier}' telah {$action}.";
    }
}
