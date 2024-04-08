<?php

namespace App\Livewire\Forms;

use App\Models\Document;
use Livewire\Attributes\Validate;
use Livewire\Form;
use Illuminate\Validation\Rule;
use Illuminate\Database\Query\Builder;
use Illuminate\Support\Facades\Storage;

class DocumentForm extends Form
{
    public $id = "";
    public $name = "";
    public $is_active = 0;
    public $description = "";

    public function mount()
    {
        $this->resetCustom();
    }

    public function rules()
    {
        return [
            "name" => ["required", Rule::unique('documents')],
            "is_active" => ["required"],
        ];
    }

    public function messages()
    {
        return [
            "name.required" => "Nama dokumen harus diisi",
            "name.unique" => "Nama dokumen harus unik",
            "is_active.required" => "Aktif atau tidak dokumen harus diisi",
        ];
    }

    public function setDocument(Document $document)
    {
        $this->fill($document->only("id", "name", "is_active", "description"));
        return $this;
    }

    public function resetCustom()
    {
        $this->reset();
    }

    public function store()
    {
        $this->validate();
        Document::create($this->all());
    }

    public function patch()
    {
    }
}
