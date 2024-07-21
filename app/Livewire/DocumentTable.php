<?php

namespace App\Livewire;

use App\Livewire\Forms\DocumentForm;
use App\Models\Document;
use Illuminate\Support\Carbon;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Support\Facades\Blade;
use Masmerise\Toaster\Toaster;
use PowerComponents\LivewirePowerGrid\Button;
use PowerComponents\LivewirePowerGrid\Column;
use PowerComponents\LivewirePowerGrid\Exportable;
use PowerComponents\LivewirePowerGrid\Facades\Filter;
use PowerComponents\LivewirePowerGrid\Footer;
use PowerComponents\LivewirePowerGrid\Header;
use PowerComponents\LivewirePowerGrid\PowerGrid;
use PowerComponents\LivewirePowerGrid\PowerGridFields;
use PowerComponents\LivewirePowerGrid\PowerGridComponent;
use PowerComponents\LivewirePowerGrid\Traits\WithExport;

final class DocumentTable extends PowerGridComponent
{
    public bool $deferLoading = true;
    protected $listeners = ["documentRefreshTable" => '$refresh'];
    use WithExport;

    public function header(): array
    {
        return [
            Button::add('destroy')
                ->render(function () {
                    return Blade::render(<<<HTML
                        <button wire:ignore.self wire:ignore type="button" wire:click="destroy" wire:confirm="Apakah anda yakin?" class="btn btn-icon btn-danger btn-md mt-2"><i class='bx bx-trash'></i></button>
                        HTML);
                }),
            Button::add('create')
                ->render(function () {
                    return Blade::render(<<<HTML
                        <button wire:ignore.self wire:ignore type="button" class="btn btn-icon btn-primary btn-md mt-2" data-bs-toggle="modal" data-bs-target="#documentCreateModal"><i class='bx bxs-plus-circle'></i></button>
                        HTML);
                }),
        ];
    }

    public function setUp(): array
    {
        $this->showCheckBox();

        return [
            Exportable::make('export')
                ->striped()
                ->type(Exportable::TYPE_XLS, Exportable::TYPE_CSV),
            Header::make()->showToggleColumns(),
            Footer::make()
                ->showPerPage()
                ->showRecordCount(),
        ];
    }

    public function datasource(): Builder
    {
        return Document::query();
    }

    public function relationSearch(): array
    {
        return [];
    }

    public function fields(): PowerGridFields
    {
        return PowerGrid::fields()
            // ->add('name')
            ->add('description', fn (Document $document) => "<span class='text-wrap'>{$document->description}</span>")
            ->add('created_at_formatted', fn (Document $document) => Carbon::parse($document->created_at)->translatedFormat("d F Y"))
            ->add('updated_at_formatted', fn (Document $document) => Carbon::parse($document->updated_at)->translatedFormat("d F Y"));
    }

    public function columns(): array
    {
        return [
            Column::make("Name", "name")
                ->sortable()
                ->searchable()->editOnClick(saveOnMouseOut: "true"),

            Column::make("Active", "is_active")
                ->toggleable(true, 1, 0),

            Column::make('Description', 'description')
                ->sortable()
                ->searchable()->editOnClick(saveOnMouseOut: "true"),

            Column::make('Created at', 'created_at_formatted', 'created_at')
                ->sortable()
                ->searchable(),

            Column::make('Updated at', 'updated_at_formatted', 'updated_at')
                ->sortable()
                ->searchable(),

            // Column::action('Action')
        ];
    }

    public function filters(): array
    {
        return [
            Filter::inputText('name', 'name')
                ->operators(['contains']),
            Filter::inputText('description', 'description')
                ->operators(['contains']),
            Filter::datepicker('created_at', 'created_at'),
            Filter::datepicker('updated_at', 'updated_at'),
        ];
    }

    #[\Livewire\Attributes\On('edit')]
    public function edit($rowId): void
    {
        $this->js('alert(' . $rowId . ')');
    }

    // public function actions(Document $row): array
    // {
    //     return [
    //         Button::add('edit')
    //             ->slot('Edit: ' . $row->id)
    //             ->class('pg-btn-white dark:ring-pg-primary-600 dark:border-pg-primary-600 dark:hover:bg-pg-primary-700 dark:ring-offset-pg-primary-800 dark:text-pg-primary-300 dark:bg-pg-primary-700')
    //             ->dispatch('edit', ['rowId' => $row->id])
    //     ];
    // }

    public function onUpdatedToggleable($id, $field, $value): void
    {
        try {
            $updated = Document::find($id)->update([
                $field => $value
            ]);
        } catch (\Throwable $th) {
            // $updated = false;
            Toaster::error("Terjadi kesalahan saat update dokumen");
            return;
        }

        $this->fillData();
        Toaster::success("Dokumen berhasil di update");
        return;
    }

    public function onUpdatedEditable($id, $field, $value): void
    {
        try {
            if ($field != "name") {
                $updated = Document::find($id)->update([
                    $field => $value
                ]);
            } else if ($value) {
                $updated = Document::find($id)->update([
                    $field => $value
                ]);
            } else {
                Toaster::error("Mohon isi nama dokumen");
                return;
            }
        } catch (\Throwable $th) {
            $updated = false;
        }

        if ($updated) {
            $this->fillData();
            Toaster::success("Dokumen berhasil di update");
            return;
        }

        Toaster::error("Terjadi kesalahan saat update dokumen");
    }

    /*
    public function actionRules($row): array
    {
       return [
            // Hide button edit for ID 1
            Rule::button('edit')
                ->when(fn($row) => $row->id === 1)
                ->hide(),
        ];
    }
    */

    public function destroy()
    {
        if (empty($this->checkedValues())) {
            Toaster::error("Silahkan pilih data");
            return;
        }

        $errorMessage = "";

        foreach (Document::whereIn("id", $this->checkedValues())->get() as $document) {
            try {
                $document->delete();
            } catch (\Throwable $th) {
                $errorMessage .= "Document: {$document->name} tidak dapat dihapus \n";
            }
        }

        $this->dispatch('documentResetForm');
        $this->dispatch('$refresh');
        Toaster::success("Data berhasil di hapus \n {$errorMessage}");
    }
}
