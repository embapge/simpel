<?php

namespace App\Livewire\Customer;

use App\DTOs\UserDTO;
use App\Models\Customer;
use App\Models\User;
use App\Services\UserService;
use Livewire\Attributes\On;
use Livewire\Component;
use Masmerise\Toaster\Toaster;

class Index extends Component
{
    #[On("customer-user-access")]
    public function storeUser(Customer $customer)
    {
        try {
            (new UserService)->store($customer->name, $customer->email, "customer");
            Toaster::success("Akses pelanggan telah dibuatkan");
        } catch (\Throwable $th) {
            Toaster::error($th->getMessage());
        }
    }

    public function render()
    {
        return view('livewire.customer.index');
    }
}
