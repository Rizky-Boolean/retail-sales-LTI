<?php

namespace App\Notifications;

use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Notifications\Messages\MailMessage;
use Illuminate\Notifications\Notification;
use App\Models\Distribusi;

class ShipmentRejectedNotification extends Notification
{
    use Queueable;

    protected $distribusi;
    protected $rejectorName;

    /**
     * Create a new notification instance.
     */
    public function __construct(Distribusi $distribusi, string $rejectorName)
    {
        $this->distribusi = $distribusi;
        $this->rejectorName = $rejectorName;
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
            'rejector_name' => $this->rejectorName,
            'message' => "Kiriman #DIST-{$this->distribusi->id} DITOLAK oleh {$this->rejectorName}.",
            'url' => route('distribusi.show', $this->distribusi->id), // URL tujuan
        ];
    }
}
