<?php

namespace App\Livewire\Forms;

use App\Models\Document;
use Livewire\Attributes\Validate;
use Livewire\Form;
use Illuminate\Validation\Rule;
use Illuminate\Database\Query\Builder;

class DocumentForm extends Form
{
    public Document $document;
    public $name;
    public $is_active;
    public $description;

    public function mount()
    {
        $this->resetCustom();
    }

    public function rules()
    {
        return [
            "name" => ["required", Rule::unique('customers')->where(fn (Builder $query) => $query->whereNot('id', $this->document->id)->whereNot("name", $this->document->name))],
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

    public function resetCustom()
    {
        $this->fill([
            "name" => "",
            "is_active" => 0,
            "description" => "",
            "document" => new Document()
        ]);
    }

    public function store()
    {
        $this->validate();
        Document::create($this->except("document"));
    }

    public function patch()
    {
    }
}
