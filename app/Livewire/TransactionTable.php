<?php

namespace App\Livewire;

use App\Models\Transaction;
use Illuminate\Support\Carbon;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Support\Facades\Blade;
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

final class TransactionTable extends PowerGridComponent
{
    protected $listeners = ['transactionRefreshTable' => '$refresh'];
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
                        <button wire:ignore.self wire:ignore type="button" class="btn btn-icon btn-primary btn-md mt-2" data-bs-toggle="modal" data-bs-target="#transactionCreateModal"><i class='bx bxs-plus-circle'></i></button>
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
        return Transaction::with(["subTypes"])->select("transactions.*");
    }

    public function relationSearch(): array
    {
        return ["subTypes" => ["name", "description"]];
    }

    public function fields(): PowerGridFields
    {
        return PowerGrid::fields()
            ->add('id')
            ->add('customer_id')
            ->add('number_display')
            ->add('total_bill')
            ->add('total')
            ->add('total_payment')
            ->add('excess_payment')
            ->add('status')
            ->add('created_at');
    }

    public function columns(): array
    {
        return [
            Column::make('Id', 'id')
                ->sortable()
                ->searchable(),

            Column::make('Customer id', 'customer_id')
                ->sortable()
                ->searchable(),

            Column::make('Number display', 'number_display')
                ->sortable()
                ->searchable(),

            Column::make('Total bill', 'total_bill')
                ->sortable()
                ->searchable(),

            Column::make('Total', 'total')
                ->sortable()
                ->searchable(),

            Column::make('Total payment', 'total_payment')
                ->sortable()
                ->searchable(),

            Column::make('Excess payment', 'excess_payment')
                ->sortable()
                ->searchable(),

            Column::make('Status', 'status')
                ->sortable()
                ->searchable(),

            Column::make('Created at', 'created_at_formatted', 'created_at')
                ->sortable(),

            Column::make('Created at', 'created_at')
                ->sortable()
                ->searchable(),

            Column::action('Action')
        ];
    }

    public function filters(): array
    {
        return [];
    }

    #[\Livewire\Attributes\On('edit')]
    public function edit($rowId): void
    {
        $this->js('alert(' . $rowId . ')');
    }

    public function actions(Transaction $row): array
    {
        return [
            Button::add('edit')
                ->slot('Edit: ' . $row->id)
                ->id()
                ->class('pg-btn-white dark:ring-pg-primary-600 dark:border-pg-primary-600 dark:hover:bg-pg-primary-700 dark:ring-offset-pg-primary-800 dark:text-pg-primary-300 dark:bg-pg-primary-700')
                ->dispatch('edit', ['rowId' => $row->id])
        ];
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
}
