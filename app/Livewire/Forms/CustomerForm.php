<?php

namespace App\Livewire\Forms;

use App\Models\Customer;
use Livewire\Attributes\On;
use Livewire\Attributes\Validate;
use Livewire\Form;
use Masmerise\Toaster\Toaster;

class CustomerForm extends Form
{
    #[Validate('required', message: "Nama harus diisi")]
    public $name;
    #[Validate('required', message: "Nama PIC harus diisi")]
    public $pic_name;
    public $group;
    public $established;
    public $emails;
    public $phones;
    public Customer $customer;

    public function mount()
    {
        $this->fill([
            "pic_name" => "",
            "group" => "",
            "established" => "",
            "emails" => collect([["id" => "", "address" => ""]]),
            "phones" => collect([["id" => "", "number" => ""]]),
        ]);
    }

    public function setCustomer(Customer $customer)
    {
        $this->fill([
            "name" => $customer->name,
            "pic_name" => $customer->pic_name,
            "group" => $customer->group,
            "established" => $customer->established,
        ]);

        foreach ($customer->emails as $iEmail => $email) {
            if ($iEmail == 0) {
                $this->emails = $this->emails->map(function ($eml) use ($email) {
                    return ["id" => $email->id, "address" => $email->address];
                });
                continue;
            }

            $this->emails->push(["id" => $email->id, "address" => $email->address]);
        }

        foreach ($customer->phones as $iPhone => $phone) {
            if ($iPhone == 0) {
                $this->phones = $this->phones->map(function ($phn) use ($phone) {
                    return ["id" => $phone->id, "number" => $phone->number];
                });
                continue;
            }

            $this->phones->push(["id" => $phone->id, "number" => $phone->number]);
        }

        $this->customer = $customer;
    }

    #[On("customer-add-email")]
    public function addEmail()
    {
        $this->emails->push(["id" => "", "address" => ""]);
    }

    public function removeEmail($id)
    {
        $this->emails->pull($id);
    }

    public function addPhone()
    {
        $this->phones->push(["id" => "", "number" => ""]);
    }

    public function removePhone($id)
    {
        $this->phones->pull($id);
    }

    public function resetCustom()
    {
        $this->reset("name", "pic_name", "group", "established");
        $this->emails = collect([["id" => "", "address" => ""]]);
        $this->phones = collect([["id" => "", "number" => ""]]);
        $this->customer = new Customer();
    }

    public function store()
    {
        $this->validate();

        $customer = Customer::create([
            "name" => $this->name,
            "pic_name" => $this->pic_name,
            "group" => $this->group,
            "established" => $this->established,
        ]);

        foreach ($this->emails as $email) {
            if (!$email['address']) {
                continue;
            }

            $customer->emails()->create([
                "address" => $email['address']
            ]);
        }

        foreach ($this->phones as $phone) {
            if (!$phone['number']) {
                continue;
            }

            $customer->phones()->create([
                "number" => $phone['number']
            ]);
        }
    }

    public function patch()
    {
        $this->validate();

        $errorMessage = "";
        $this->customer->update($this->except("emails", "phones", "customer"));

        foreach ($this->emails as $email) {
            if ($email['id'] && $this->customer->emails->where("id", $email['id'])->isNotEmpty()) {
                $newEmail = $this->customer->emails->where("id", $email['id'])->first();
                if ($email['address']) {
                    $newEmail->update([
                        "address" => $email['address']
                    ]);
                } else {
                    try {
                        $newEmail->delete();
                    } catch (\Throwable $th) {
                        $errorMessage .= "Email: {$newEmail['address']} tidak dapat dihapus \n";
                    }
                }
            } else {
                if ($email['address']) {
                    $this->customer->emails()->create([
                        "address" => $email['address']
                    ]);
                }
            }
        }

        foreach ($this->customer->emails->whereNotIn("id", $this->emails->pluck("id")) as $email) {
            try {
                $email->delete();
            } catch (\Throwable $th) {
                $errorMessage .= "Email: {$email['address']} tidak dapat dihapus \n";
            }
        }

        foreach ($this->phones as $phone) {
            if ($phone['id'] && $this->customer->phones->where("id", $phone['id'])->isNotEmpty()) {
                $newphone = $this->customer->phones->where("id", $phone['id'])->first();
                if ($phone['number']) {
                    $newphone->update([
                        "number" => $phone['number']
                    ]);
                } else {
                    try {
                        $newphone->delete();
                    } catch (\Throwable $th) {
                        $errorMessage .= "Phone: {$newphone['number']} tidak dapat dihapus \n";
                    }
                }
            } else {
                if ($phone['number']) {
                    $this->customer->phones()->create([
                        "number" => $phone['number']
                    ]);
                }
            }
        }

        foreach ($this->customer->phones->whereNotIn("id", $this->phones->pluck("id")) as $phone) {
            try {
                $phone->delete();
            } catch (\Throwable $th) {
                $errorMessage .= "Phone: {$phone['number']} tidak dapat dihapus \n";
            }
        }

        Toaster::success("Data berhasil diubah \n {$errorMessage}");
    }

    public function destroy(array $customer)
    {
        dd("masuk sini");
    }
}
