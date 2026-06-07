<?php

namespace App\Livewire\Warehouse;

use Livewire\Component;
use Livewire\WithPagination;
use Livewire\Attributes\Layout;
use Illuminate\Support\Facades\Auth;

#[Layout('admin.layouts.app')]
class NotificationInbox extends Component
{
    use WithPagination;

    public $selectedNotification = null;

    public function render()
    {
        if (config('warehouse.automation_mode') === 'disabled') {
            abort(404, 'Warehouse engine disabled.');
        }

        $notifications = Auth::user()->notifications()->paginate(20);

        return view('livewire.warehouse.notification-inbox', [
            'notifications' => $notifications,
        ]);
    }

    public function markAsRead($notificationId)
    {
        $notification = Auth::user()->notifications()->where('id', $notificationId)->first();
        if ($notification) {
            $notification->markAsRead();
        }
    }

    public function markAllAsRead()
    {
        Auth::user()->unreadNotifications->markAsRead();
        $this->selectedNotification = null;
    }

    public function viewDetails($notificationId)
    {
        $this->selectedNotification = Auth::user()->notifications()->where('id', $notificationId)->first();
        $this->markAsRead($notificationId);
    }

    public function closeDetails()
    {
        $this->selectedNotification = null;
    }
}
