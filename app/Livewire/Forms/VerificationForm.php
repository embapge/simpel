<?php

namespace App\Livewire\Forms;

use App\Events\CustomerRegistratedEvent;
use App\Models\Customer;
use App\Models\CustomerEmail;
use App\Models\Verification;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Storage;
use Livewire\Attributes\Validate;
use Livewire\Form;
use Spatie\UrlSigner\Laravel\Facades\UrlSigner;

class VerificationForm extends Form
{
    public $id;
    public $transaction_sub_type_id;
    public $name;
    public $pic_name;
    public $address;
    public $email;
    public $verify_at;
    public $phone_number;
    public $website;
    public $status;

    public function setVerification(Verification $verification)
    {
        $this->fill($verification->only("id", "transaction_sub_type_id", "name", "pic_name", "address", "email", "verify_at", "phone_number", "website", "status"));
    }

    public function resendLink($message)
    {
        $verification = Verification::find($this->id);
        $verification->fresh();
        $valid = UrlSigner::validate($verification->link);

        if (!$valid) {
            $url = UrlSigner::sign("http://localhost:3000/registration/{$verification->id}/upload");

            $verification->update([
                "link" => $url,
                "status" => "upload_file"
            ]);
        }

        $verification->fresh();

        CustomerRegistratedEvent::dispatch($verification, $verification->link, $message);

        return $this;
    }

    public function succed()
    {
        $verification = Verification::where("id", $this->id)->with(["documents", "emails", "phones"])->first();
        $this->fill([
            "status" => "success"
        ]);
        $verification->update($this->only("status"));

        $customer = Customer::where([["name", "=", $verification->name], ["email", "=", $verification->email], ["phone_number", "=", $verification->phone_number], ["address", "=", $verification->address]])->first();

        if ($customer) {
            return false;
        }

        $customer = Customer::create($verification->only("name", "email", "phone_number", "pic_name", "address", "website"));

        foreach ($verification->emails as $email) {
            $customer->emails()->create([
                "address" => $email->address,
                "name" => $email->name,
            ]);
        }

        foreach ($verification->phones as $phone) {
            $customer->phones()->create([
                "number" => $phone->number,
                "name" => $phone->name,
            ]);
        }

        $transaction = $customer->transactions()->create([
            "transaction_sub_type_id" => $verification->transaction_sub_type_id
        ]);

        $transactionDirectory = "private/transactions/{$transaction->id}";
        if (!Storage::exists($transactionDirectory)) {
            Storage::makeDirectory($transactionDirectory); //creates directory
        }

        $documentDirectory = $transactionDirectory . "/documents";

        if (!Storage::exists($documentDirectory)) {
            Storage::makeDirectory($documentDirectory); //creates directory
        }

        foreach ($verification->documents as $document) {

            $transaction->documents()->attach($document->document_id, ["file" => $document->file]);

            if (Storage::exists($document->file)) {
                Storage::move($document->file, $documentDirectory);
            }
        }

        return $transaction;
    }
}
