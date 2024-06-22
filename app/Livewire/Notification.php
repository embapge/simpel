<?php

namespace App\Livewire;

use App\Models\Payment;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Notification as FacadesNotification;
use Livewire\Attributes\On;
use Livewire\Component;
use Masmerise\Toaster\Toast;
use Masmerise\Toaster\Toaster;

class Notification extends Component
{
    public $count = 0;
    public $paymentId = "9c004210-021b-405e-94c5-a543fff4be40";
    public $notifications;
    public $user;

    public function mount()
    {
        $this->user = auth()->user();
        $this->count = $this->user->notifications->count();
    }

    public function getListeners()
    {
        return [
            "echo-private:App.Models.User.{$this->user->id},.Illuminate\\Notifications\\Events\\BroadcastNotificationCreated" => 'notify',
            // "echo:payment.check,MidtransTransactionStatusEvent" => 'notify'
        ];
    }

    public function notify($event)
    {
        $this->user->fresh();
        $this->count = $this->user->notifications->count();

        if ($event['status'] == "expire" || $event['status'] == "cancel") {
            Toaster::error($event['message']);
        } else if ($event['status'] == "pending") {
            Toaster::error($event['message']);
        } else {
            Toaster::success($event['message']);
        }
    }

    public function render()
    {
        return view('livewire.notification');
    }
}
