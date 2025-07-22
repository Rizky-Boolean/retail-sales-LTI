<?php

namespace App\Notifications;

use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Notifications\Messages\MailMessage;
use Illuminate\Notifications\Notification;
use App\Models\Distribusi;

class ShipmentReceivedNotification extends Notification
{
    use Queueable;

    protected $distribusi;
    protected $receiverName;

    /**
     * Create a new notification instance.
     */
    public function __construct(Distribusi $distribusi, string $receiverName)
    {
        $this->distribusi = $distribusi;
        $this->receiverName = $receiverName;
    }

    /**
     * Get the notification's delivery channels.
     */
    public function via(object $notifiable): array
    {
        return ['database'];
    }

    /**
     * Get the array representation of the notification.
     */
    public function toArray(object $notifiable): array
    {
        return [
            'distribusi_id' => $this->distribusi->id,
            'receiver_name' => $this->receiverName,
            'message' => "Kiriman #DIST-{$this->distribusi->id} telah diterima oleh {$this->receiverName}.",
            'url' => route('distribusi.show', $this->distribusi->id), // URL tujuan
        ];
    }
}
