<?php

namespace App\Notifications;

use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Notifications\Messages\MailMessage;
use Illuminate\Notifications\Notification;
use App\Models\Distribusi; // Import model Distribusi

class NewDistributionNotification extends Notification
{
    use Queueable;

    protected $distribusi;

    /**
     * Create a new notification instance.
     */
    public function __construct(Distribusi $distribusi)
    {
        $this->distribusi = $distribusi;
    }

    /**
     * Get the notification's delivery channels.
     * Kita hanya akan menyimpannya di database untuk saat ini.
     *
     * @return array<int, string>
     */
    public function via(object $notifiable): array
    {
        return ['database'];
    }

    /**
     * Get the array representation of the notification.
     * Ini adalah data yang akan disimpan sebagai JSON di database.
     *
     * @return array<string, mixed>
     */
    public function toArray(object $notifiable): array
    {
        return [
            'distribusi_id' => $this->distribusi->id,
            'sender_name' => $this->distribusi->user->name,
            'message' => "Kiriman baru #DIST-{$this->distribusi->id} telah dibuat.",
            'url' => route('cabang.penerimaan.index'), // URL tujuan saat notifikasi diklik
        ];
    }
}
