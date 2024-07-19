<?php

namespace App\Livewire;

use App\Enums\CustomerType;
use App\Enums\TransactionStatus;
use App\Models\Customer;
use App\Models\Transaction;
use Illuminate\Support\Carbon;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Support\Facades\Auth;
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
use Illuminate\Support\Str;
use Masmerise\Toaster\Toaster;
use NumberFormatter;

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

    public function datasource(): ?Builder
    {
        $transaction = Transaction::query()->join("customers", fn ($customer) => $customer->on("transactions.customer_id", "=", "customers.id"))->select(["transactions.*", "customers.name as customer_name"]);

        if (Auth::user()->role == "customer") {
            $transaction = $transaction->where("customer_id", Auth::user()->customer->first()->id);
        }

        return $transaction;
    }

    public function relationSearch(): array
    {
        return [
            "customer" => ["name"]
        ];
    }

    public function fields(): PowerGridFields
    {
        $idr = new NumberFormatter('id_ID', NumberFormatter::CURRENCY);
        return PowerGrid::fields()
            ->add('customers.name')
            ->add('customer_name', fn (Transaction $transaction) => Str::title($transaction->customer->name))
            ->add('number_display', fn (Transaction $transaction) => "<a href='" . route("transaction.detail", ["transaction" => $transaction->id]) . "'>{$transaction->number_display}</a>")
            ->add('total_bill', fn (Transaction $transaction) => $idr->formatCurrency(round((int)$transaction->total_bill), "IDR"))
            // ->add('total_bill', fn (Transaction $transaction) => round((int)$transaction->total_bill))
            ->add('total', fn (Transaction $transaction) => $idr->formatCurrency(round((int)$transaction->total), "IDR"))
            ->add('total_payment', fn (Transaction $transaction) => $idr->formatCurrency(round((int)$transaction->total_payment), "IDR"))
            ->add('excess_payment', fn (Transaction $transaction) => $idr->formatCurrency(round((int)$transaction->excess_payment), "IDR"))
            ->add('status')
            ->add('created_at_formatted', fn (Transaction $transaction) => Carbon::parse($transaction->created_at)->translatedFormat("d F Y"))
            ->add('updated_at_formatted', fn (Transaction $transaction) => Carbon::parse($transaction->updated_at)->translatedFormat("d F Y"));
    }

    public function columns(): array
    {
        return [
            Column::make('Number display', 'number_display')
                ->sortable()
                ->searchable(),

            Column::make('Customer', 'customer_name', 'customers.name')
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

            Column::make('Updated at', 'updated_at_formatted', 'updated_at')
                ->sortable()
                ->searchable(),
        ];
    }

    public function filters(): array
    {
        return [
            Filter::inputText('number_display', 'number_display')
                ->operators(['contains']),
            Filter::inputText('customer_name', 'customers.name')
                ->operators(['contains']),
            Filter::number('total_bill', 'total_bill')
                ->thousands('.')
                ->decimal(','),
            Filter::number('total', 'total')
                ->thousands('.')
                ->decimal(','),
            Filter::number('total_payment', 'total_payment')
                ->thousands('.')
                ->decimal(','),
            Filter::number('excess_payment', 'excess_payment')
                ->thousands('.')
                ->decimal(','),
            Filter::multiSelect('status', 'status')
                ->dataSource(TransactionStatus::array())
                ->optionValue('id')
                ->optionLabel('name'),
            Filter::datepicker('created_at', 'created_at'),
            Filter::datepicker('updated_at', 'updated_at'),
        ];
    }

    #[\Livewire\Attributes\On('edit')]
    public function edit($rowId): void
    {
        $this->js('alert(' . $rowId . ')');
    }

    // public function actions(Transaction $row): array
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

        foreach (Transaction::whereIn("id", $this->checkedValues())->get() as $transaction) {
            try {
                $transaction->delete();
            } catch (\Throwable $th) {
                $errorMessage .= "Transaksi Id: {$transaction->id}, Number Display: {$transaction->number_display} tidak dapat dihapus \n";
            }
        }

        $this->dispatch('$refresh');
        Toaster::success("Data berhasil di hapus \n {$errorMessage}");
    }
}
