<?php

namespace App\Notifications\Warehouse;

use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Notifications\Messages\MailMessage;
use Illuminate\Notifications\Notification;

class WarehouseAlertNotification extends Notification implements ShouldQueue
{
    use Queueable;

    public string $eventType;
    public string $severity;
    public string $message;
    public ?int $batchId;

    public function __construct(string $eventType, string $severity, string $message, ?int $batchId = null)
    {
        $this->eventType = $eventType;
        $this->severity = $severity;
        $this->message = $message;
        $this->batchId = $batchId;
    }

    public function via($notifiable): array
    {
        // CRITICAL events trigger both Database and Email. Others just Database.
        return strtoupper($this->severity) === 'CRITICAL' ? ['database', 'mail'] : ['database'];
    }

    public function toMail($notifiable): MailMessage
    {
        return (new MailMessage)
                    ->error()
                    ->subject("Warehouse Alert [{$this->severity}]: {$this->eventType}")
                    ->greeting("Hello,")
                    ->line("A warehouse alert requires your attention.")
                    ->line("Event: {$this->eventType}")
                    ->line("Severity: {$this->severity}")
                    ->line("Batch ID: " . ($this->batchId ?? 'N/A'))
                    ->line("Message: {$this->message}")
                    ->action('View Dashboard', url('/'))
                    ->line('Please investigate the issue immediately.');
    }

    public function toDatabase($notifiable): array
    {
        return [
            'event_type' => $this->eventType,
            'severity' => strtoupper($this->severity),
            'batch_id' => $this->batchId,
            'message' => $this->message,
            'created_at' => now()->toIso8601String(),
        ];
    }
}
