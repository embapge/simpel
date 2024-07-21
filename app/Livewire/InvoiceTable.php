<?php

namespace App\Livewire;

use App\Enums\InvoiceType;
use App\Models\Customer;
use App\Models\Invoice;
use App\Models\Transaction;
use Illuminate\Support\Carbon;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Blade;
use Livewire\Attributes\On;
use Masmerise\Toaster\Toaster;
use NumberFormatter;
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
    public bool $deferLoading = true;

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

    public function datasource(): ?Builder
    {
        $invoice = Invoice::with(["transaction"])->select("invoices.*");
        if (Auth::user()->role == "customer") {
            $invoice = $invoice->withWhereHas("transaction", function ($q) {
                $q->withWhereHas("customer", function ($q) {
                    $q->where("id", Auth::user()->customer->first()->id);
                });
            });
        }

        return $invoice;
    }

    public function relationSearch(): array
    {
        return [];
    }

    public function fields(): PowerGridFields
    {
        $idr = new NumberFormatter('id_ID', NumberFormatter::CURRENCY);
        return PowerGrid::fields()
            // ->add('transaction_id', fn (Invoice $invoice) => "<a href='" . route("transaction.detail", ["transaction" => $invoice->transaction_id]) . "'>{$invoice->transaction->number_display}</a>")
            ->add('transaction_id', fn (Invoice $invoice) => "<a href='" . route("transaction.detail", ["transaction" => $invoice->transaction_id]) . "'>{$invoice->transaction->number_display}</a>")
            ->add('number_display', fn (Invoice $invoice) => "<a href='" . route("invoice.detail", ["invoice" => $invoice->id]) . "'>{$invoice->number_display}</a>")
            ->add('type', fn (Invoice $invoice) => InvoiceType::from($invoice->type)->labels())
            ->add('subtotal', fn (Invoice $invoice) => $idr->formatCurrency(round((int)$invoice->subtotal), "IDR"))
            ->add('total', fn (Invoice $invoice) => $idr->formatCurrency(round((int)$invoice->total), "IDR"))
            ->add('total_bill', fn (Invoice $invoice) => $idr->formatCurrency(round((int)$invoice->total_bill), "IDR"))
            ->add('total_payment', fn (Invoice $invoice) => $idr->formatCurrency(round((int)$invoice->total_payment), "IDR"))
            ->add('excess_payment', fn (Invoice $invoice) => $idr->formatCurrency(round((int)$invoice->excess_payment), "IDR"))
            ->add('tax', fn (Invoice $invoice) => $idr->formatCurrency(round((int)$invoice->tax), "IDR"))
            ->add('stamp', fn (Invoice $invoice) => $idr->formatCurrency(round((int)$invoice->stamp), "IDR"))
            ->add('customer_name')
            ->add('customer_pic_name')
            ->add('customer_address')
            ->add('internal_note')
            ->add('created_at_formatted', fn (Invoice $invoice) => Carbon::parse($invoice->created_at)->translatedFormat("d F Y"))
            ->add('updated_at_formatted', fn (Invoice $invoice) => Carbon::parse($invoice->updated_at)->translatedFormat("d F Y"));
    }

    public function columns(): array
    {
        return [
            Column::make('Number display', 'number_display')
                ->sortable()
                ->searchable(),

            Column::make('Type', 'type')
                ->sortable()
                ->searchable(),

            Column::make('Subtotal', 'subtotal')
                ->sortable()
                ->searchable(),

            Column::make('Tax', 'tax')
                ->sortable()
                ->searchable(),

            Column::make('Stamp', 'stamp')
                ->sortable()
                ->searchable(),

            Column::make('Total payment', 'total_payment')
                ->sortable()
                ->searchable(),

            Column::make('Excess payment', 'excess_payment')
                ->sortable()
                ->searchable(),

            Column::make('Total', 'total')
                ->sortable()
                ->searchable(),

            Column::make('Total bill', 'total_bill')
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

            Column::make('Updated at', 'updated_at_formatted', 'updated_at')
                ->sortable(),
        ];
    }

    public function filters(): array
    {
        return [
            Filter::number('subtotal', 'subtotal')
                ->thousands('.')
                ->decimal(','),
            Filter::number('tax', 'tax')
                ->thousands('.')
                ->decimal(','),
            Filter::number('stamp', 'stamp')
                ->thousands('.')
                ->decimal(','),
            Filter::number('total', 'total')
                ->thousands('.')
                ->decimal(','),
            Filter::number('total_bill', 'total_bill')
                ->thousands('.')
                ->decimal(','),
            Filter::number('total_payment', 'total_payment')
                ->thousands('.')
                ->decimal(','),
            Filter::number('excess_payment', 'excess_payment')
                ->thousands('.')
                ->decimal(','),
            Filter::multiSelect('type', 'type')
                ->dataSource(InvoiceType::array())
                ->optionValue('id')
                ->optionLabel('name'),
            Filter::inputText("number_display", "number_display"),
            Filter::inputText("customer_name", "customer_name"),
            Filter::inputText("customer_pic_name", "customer_pic_name"),
            Filter::inputText("customer_address", "customer_address"),
            Filter::inputText("internal_note", "internal_note"),
            Filter::datepicker('created_at_formatted', 'created_at'),
            Filter::datepicker('updated_at_formatted', 'updated_at'),
        ];
    }

    #[\Livewire\Attributes\On('edit')]
    public function edit($rowId): void
    {
        $this->js('alert(' . $rowId . ')');
    }

    // public function actions(Invoice $row): array
    // {
    //     return [
    //         Button::add('edit')
    //             ->slot('Edit: ' . $row->id)
    //             ->id()
    //             ->class('pg-btn-white dark:ring-pg-primary-600 dark:border-pg-primary-600 dark:hover:bg-pg-primary-700 dark:ring-offset-pg-primary-800 dark:text-pg-primary-300 dark:bg-pg-primary-700')
    //             ->dispatch('edit', ['rowId' => $row->id])
    //     ];
    // }

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
