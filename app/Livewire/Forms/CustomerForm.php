<?php

namespace App\Livewire\Forms;

use App\Models\Customer;
use Illuminate\Database\Query\Builder;
use Illuminate\Support\Collection;
use Illuminate\Validation\Rule;
use Livewire\Attributes\On;
use Livewire\Attributes\Validate;
use Livewire\Form;
use Masmerise\Toaster\Toaster;

class CustomerForm extends Form
{
    public $name;
    public $email;
    public $phone_number;
    public $pic_name;
    public $website;
    public $address;
    public $emails;
    public $phones;
    public Customer $customer;

    public function mount()
    {
        $this->resetCustom();
        // $this->fill([
        //     "name" => "",
        //     "email" => "",
        //     "phone_number" => "",
        //     "pic_name" => "",
        //     "address" => "",
        //     "website" => "",
        //     "emails" => collect([["id" => "", "address" => "", "name" => ""]]),
        //     "phones" => collect([["id" => "", "number" => "", "name" => ""]]),
        // ]);
        // $this->customer = new Customer();
    }

    public function rules()
    {
        return [
            'name' => "required",
            // 'email' => "email:rfc,dns,spoof",
            'email' => ["required", Rule::unique('customers')->where(fn (Builder $query) => $query->whereNot('id', $this->customer->id)->whereNot("email", $this->customer->email))],
            'phone_number' => ["required", Rule::unique('customers')->where(fn (Builder $query) => $query->whereNot('id', $this->customer->id)->whereNot("phone_number", $this->customer->phone_number))],
            'pic_name' => "required",
            'address' => "required",
            "emails.*.address" => "required_with:emails.*.name",
            "emails.*.name" => "required_with:emails.*.address",
            "phones.*.number" => "required_with:phones.*.name",
            "phones.*.name" => "required_with:phones.*.number",
        ];
    }

    public function messages()
    {
        return [
            'name.required' => 'Nama harus diisi.',
            'email.required' => 'Email harus diisi.',
            'email.email' => 'Email tidak valid.',
            'email.unique' => 'Email harus unik.',
            'phone_number.required' => 'Nomor Telepon harus diisi.',
            'phone_number.unique' => 'Nomor Telepon harus unik.',
            'pic_name.required' => 'Nama PIC harus diisi.',
            'pic_name.required' => 'Alamat harus diisi.',
            'emails.*.name' => [
                'required_with' => "Nama Email harus diisi dengan Alamat Email"
            ],
            'emails.*.address' => [
                'required_with' => "Alamat Email harus diisi dengan Nama Email"
            ],
            'phones.*.name' => [
                'required_with' => "Nama Telepon harus diisi dengan Nomor Telepon"
            ],
            'phones.*.number' => [
                'required_with' => "Nomor Telepon harus diisi dengan Nama Telepon"
            ],
        ];
    }

    public function setCustomer(Customer $customer)
    {
        $this->fill([
            "name" => $customer->name,
            "email" => $customer->email,
            "phone_number" => $customer->phone_number,
            "pic_name" => $customer->pic_name,
            "address" => $customer->address,
            "website" => $customer->website,
        ]);

        foreach ($customer->emails as $iEmail => $email) {
            if ($iEmail == 0) {
                $this->emails = $this->emails->map(function ($eml) use ($email) {
                    return ["id" => $email->id, "address" => $email->address, "name" => $email->name];
                });
                continue;
            }

            $this->emails->push(["id" => $email->id, "address" => $email->address, "name" => $email->name]);
        }

        foreach ($customer->phones as $iPhone => $phone) {
            if ($iPhone == 0) {
                $this->phones = $this->phones->map(function ($eml) use ($phone) {
                    return ["id" => $phone->id, "number" => $phone->number, "name" => $phone->name];
                });
                continue;
            }

            $this->phones->push(["id" => $phone->id, "number" => $phone->number, "name" => $phone->name]);
        }

        $this->customer = $customer;
    }

    #[On("customer-add-email")]
    public function addEmail()
    {
        $this->emails->push(["id" => "", "address" => "", "name" => ""]);
    }

    public function removeEmail($id)
    {
        $this->emails->pull($id);
    }

    public function addPhone()
    {
        $this->phones->push(["id" => "", "number" => "", "name" => ""]);
    }

    public function removePhone($id)
    {
        $this->phones->pull($id);
    }

    public function resetCustom()
    {
        $this->reset("name", "email", "phone_number", "pic_name", "address", "website");
        $this->emails = collect([["id" => "", "address" => "", "name" => ""]]);
        $this->phones = collect([["id" => "", "number" => "", "name" => ""]]);
        $this->customer = new Customer();
    }

    public function store()
    {
        $this->validate();

        $customer = Customer::create([
            "name" => $this->name,
            "email" => $this->email,
            "phone_number" => $this->phone_number,
            "pic_name" => $this->pic_name,
            "address" => $this->address,
            "website" => $this->website,
        ]);

        foreach ($this->emails as $email) {
            if (!$email['address']) {
                continue;
            }

            $customer->emails()->create([
                "address" => $email['address'],
                "name" => $email['name']
            ]);
        }

        foreach ($this->phones as $phone) {
            if (!$phone['number']) {
                continue;
            }

            $customer->phones()->create([
                "number" => $phone['number'],
                "name" => $phone['name'],
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
                        "address" => $email['address'],
                        "name" => $email['name'],
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
                        "address" => $email['address'],
                        "name" => $email['name'],
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
                        "number" => $phone['number'],
                        "name" => $phone['name'],
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
                        "number" => $phone['number'],
                        "name" => $phone['name'],
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

    public function destroy(array $customers)
    {
        $errorMessage = "";

        foreach ($customers as $customer) {
            try {
                $customer->delete();
            } catch (\Throwable $th) {
                $errorMessage .= "Customer: {$customer->name} tidak dapat dihapus \n";
            }
        }

        $this->resetCustom();
        Toaster::success("Data berhasil di hapus \n {$errorMessage}");
    }
}
