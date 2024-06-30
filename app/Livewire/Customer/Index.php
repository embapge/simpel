<?php

namespace App\Livewire\Customer;

use App\DTOs\UserDTO;
use App\Mail\CustomerAppAccessMail;
use App\Models\Customer;
use App\Models\User;
use App\Services\UserService;
use Illuminate\Support\Facades\Mail;
use Livewire\Attributes\On;
use Livewire\Component;
use Masmerise\Toaster\Toaster;

class Index extends Component
{
    #[On("customer-user-access")]
    public function storeUser(Customer $customer)
    {
        try {
            $user = (new UserService)->storeForCustomer($customer);
            Mail::to("baratagusti.bg@gmail.com")->queue(new CustomerAppAccessMail);
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
