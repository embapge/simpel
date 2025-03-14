<?php

namespace App\Livewire\Forms;

use App\Models\Document;
use App\Models\Transaction;
use Carbon\Carbon;
use Illuminate\Support\Facades\Storage;
use Livewire\Attributes\Validate;
use Livewire\Form;

class TransactionDocumentForm extends Form
{
    public $transaction;
    public $document;
    public $document_id = "";
    public $transaction_id = "";
    public $date = "";
    public $file = "";

    public function rules()
    {
        return [
            "transaction_id" => ["required"],
            "document_id" => ["required"],
        ];
    }

    public function messages()
    {
        return [
            "transaction_id.required" => "Transaksi harus diisi",
            "document_id.required" => "Dokumen harus diisi",
        ];
    }

    public function setTransactionDocument($transaction = null, $document = null)
    {
        $this->fill([
            "transaction" => $transaction,
            "document" => $document,
            "transaction_id" => $transaction->id ?? null,
            "document_id" => $document->id ?? null,
            "date" => $document->pivot->date ?? null,
            "file" => $document->pivot->file ?? null,
        ]);

        return $this;
    }

    public function store()
    {
        $this->transaction->refresh();
        if ($this->transaction->documents->where("id", $this->document_id)->isEmpty()) {
            $this->transaction->documents()->attach($this->document_id);
            $this->document = Document::find($this->document_id);
            $this->setTransactionDocument($this->transaction, $this->document);
            return true;
        }
        return false;
    }

    public function destroy()
    {
        if ($this->file && Storage::exists($this->file)) {
            Storage::delete($this->file);
        }

        $this->transaction->documents()->detach($this->document->id);
    }

    public function empty()
    {
        if ($this->file && Storage::exists($this->file)) {
            Storage::delete($this->file);

            $this->transaction->documents()->updateExistingPivot($this->document->id, [
                "file" => "",
                "date" => null,
            ]);

            $this->reset("file", "date");
        }
    }

    public function upload()
    {
        $this->fill([
            "file" => $this->file,
        ]);

        if ($this->file) {
            $transactionDirectory = "private/transactions/{$this->transaction->id}";
            if (!Storage::exists($transactionDirectory)) {
                Storage::makeDirectory($transactionDirectory); //creates directory
            }

            $documentDirectory = $transactionDirectory . "/documents";

            if (!Storage::exists($documentDirectory)) {
                Storage::makeDirectory($documentDirectory); //creates directory
            }

            // Jika File nya sama maka tidak di upload ulang/ Jika berbeda maka di upload ulang
            if ($this->transaction->documents->where("id", $this->document->id)->first()->pivot->file != $this->file) {

                // Jika file existing ada maka dihapus terlebih dahulu
                if ($this->transaction->documents->where("id", $this->document->id)->first()->pivot->file) {
                    Storage::delete($this->transaction->documents->where("id", $this->document->id)->first()->pivot->file);
                }

                $path = Storage::putFile($documentDirectory, $this->file, "private");

                Transaction::find($this->transaction->id)->documents()->updateExistingPivot($this->document->id, [
                    "date" => Carbon::now(),
                    "file" => $path,
                ]);
            }
        }

        return $this;
    }
}
