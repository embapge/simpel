<?php

namespace App\Livewire\Forms;

use App\Models\VerificationDocument;
use Illuminate\Support\Facades\Storage;
use Livewire\Attributes\Validate;
use Livewire\Form;

class VerificationDocumentForm extends Form
{
    public $id;
    public $name;
    public $verification_id;
    public $document_id;
    public $date;
    public $file;
    public $is_verified;

    public function setDocument(VerificationDocument $verificationDocument)
    {
        $verificationDocument->load("document");
        $this->fill($verificationDocument->only("id", "verification_id", "document_id", "date", "file", "is_verified"));
        $this->fill(["name" => $verificationDocument->document->name]);

        return $this;
    }

    public function verified()
    {
        $this->is_verified = 1;
        VerificationDocument::find($this->id)->update($this->only("is_verified"));

        return $this;
    }

    public function unverified()
    {
        $this->is_verified = 0;
        $verificationDocument = VerificationDocument::where("id", $this->id)->with(["verification"])->first();
        $verificationDocument->update($this->only("is_verified"));
        $verificationDocument->verification->update(["status" => "upload_file"]);
        if ($this->file && Storage::exists($this->file)) {
            $verificationDocument->update([
                "file" => null,
                "date" => null,
            ]);
            Storage::delete($this->file);
            $this->reset("file", "date");
        }

        return $this;
    }
}
