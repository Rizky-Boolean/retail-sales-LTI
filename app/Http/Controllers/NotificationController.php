<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class NotificationController extends Controller
{
    /**
     * Menandai notifikasi sebagai sudah dibaca dan mengarahkan pengguna.
     */
    public function markAsRead($id)
    {
        $user = Auth::user();
        $notification = $user->unreadNotifications()->where('id', $id)->first();

        if ($notification) {
            $notification->markAsRead();
            // Arahkan pengguna ke URL yang tersimpan di dalam notifikasi
            return redirect($notification->data['url']);
        }

        // Jika notifikasi tidak ditemukan, arahkan ke dashboard saja
        return redirect()->route('dashboard');
    }
}