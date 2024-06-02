<?php

namespace App\Livewire;

use App\Enums\InvoiceType;
use App\Models\Customer;
use App\Models\Invoice;
use App\Models\Transaction;
use Illuminate\Support\Carbon;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Support\Facades\Blade;
use Livewire\Attributes\On;
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

final class InvoiceTable extends PowerGridComponent
{
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
        ];
    }

    public function setUp(): array
    {
        $this->showCheckBox();

        return [
            Exportable::make('export')
                ->striped()
                ->type(Exportable::TYPE_XLS, Exportable::TYPE_CSV),
            Header::make()->showSearchInput(),
            Footer::make()
                ->showPerPage()
                ->showRecordCount(),
        ];
    }

    public function datasource(): Builder
    {
        return Invoice::query();
        return Invoice::query()
            ->join("transactions", fn (Transaction $transaction) => $transaction->on("transactions.id", "=", "invoices.transaction_id"))
            ->select("invoices.*");
    }

    public function relationSearch(): array
    {
        return [];
    }

    public function fields(): PowerGridFields
    {
        return PowerGrid::fields()
            ->add('transaction_id', fn (Invoice $invoice) => "<a href='" . route("transaction.detail", ["transaction" => $invoice->transaction_id]) . "'>{$invoice->transaction->number_display}</a>")
            ->add('number_display', fn (Invoice $invoice) => "<a href='" . route("invoice.detail", ["invoice" => $invoice->id]) . "'>{$invoice->number_display}</a>")
            ->add('type', fn (Invoice $invoice) => InvoiceType::from($invoice->type)->labels())
            ->add('subtotal')
            ->add('total')
            ->add('total_bill')
            ->add('total_payment')
            ->add('excess_payment')
            ->add('tax')
            ->add('stamp')
            ->add('customer_name')
            ->add('customer_pic_name')
            ->add('customer_address')
            ->add('internal_note')
            ->add('created_at');
    }

    public function columns(): array
    {
        return [
            Column::make('Number display', 'number_display')
                ->sortable()
                ->searchable(),

            Column::make('Transaction id', 'transaction_id')
                ->sortable()
                ->searchable(),

            Column::make('Type', 'type')
                ->sortable()
                ->searchable(),

            Column::make('Subtotal', 'subtotal')
                ->sortable()
                ->searchable(),

            Column::make('Total', 'total')
                ->sortable()
                ->searchable(),

            Column::make('Total bill', 'total_bill')
                ->sortable()
                ->searchable(),

            Column::make('Total payment', 'total_payment')
                ->sortable()
                ->searchable(),

            Column::make('Excess payment', 'excess_payment')
                ->sortable()
                ->searchable(),

            Column::make('Tax', 'tax')
                ->sortable()
                ->searchable(),

            Column::make('Stamp', 'stamp')
                ->sortable()
                ->searchable(),

            Column::make('Customer name', 'customer_name')
                ->sortable()
                ->searchable(),

            Column::make('Customer pic name', 'customer_pic_name')
                ->sortable()
                ->searchable(),

            Column::make('Customer address', 'customer_address')
                ->sortable()
                ->searchable(),

            Column::make('Internal note', 'internal_note')
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

    public function actions(Invoice $row): array
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

    public function destroy()
    {
        if (empty($this->checkedValues())) {
            Toaster::error("Silahkan pilih data");
            return;
        }

        $errorMessage = "";

        foreach (Invoice::whereIn("id", $this->checkedValues())->get() as $invoice) {
            try {
                $invoice->delete();
            } catch (\Throwable $th) {
                $errorMessage .= "Transaksi Id: {$invoice->id}, Number Display: {$invoice->number_display} tidak dapat dihapus \n";
            }
        }

        $this->dispatch('$refresh');
        Toaster::success("Data berhasil di hapus \n {$errorMessage}");
    }
}
