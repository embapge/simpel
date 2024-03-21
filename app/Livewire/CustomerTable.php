<?php

namespace App\Livewire;

use App\Enums\CustomerType;
use App\Models\Customer;
use Illuminate\Support\Carbon;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Support\Facades\Blade;
use Masmerise\Toaster\Toast;
use Masmerise\Toaster\Toaster;
use PowerComponents\LivewirePowerGrid\Button;
use PowerComponents\LivewirePowerGrid\Column;
use PowerComponents\LivewirePowerGrid\Components\Rules\Support\SetAttributeRule;
use PowerComponents\LivewirePowerGrid\Exportable;
use PowerComponents\LivewirePowerGrid\Facades\Filter;
use PowerComponents\LivewirePowerGrid\Facades\Rule;
use PowerComponents\LivewirePowerGrid\Footer;
use PowerComponents\LivewirePowerGrid\Header;
use PowerComponents\LivewirePowerGrid\PowerGrid;
use PowerComponents\LivewirePowerGrid\PowerGridFields;
use PowerComponents\LivewirePowerGrid\PowerGridComponent;
use PowerComponents\LivewirePowerGrid\Traits\WithExport;

final class CustomerTable extends PowerGridComponent
{
    protected $listeners = ['customerRefreshTable' => '$refresh'];
    use WithExport;
    public function header(): array
    {
        return [
            Button::add('destroy')
                ->render(function () {
                    return Blade::render(<<<HTML
                        <button wire:ignore.self wire:ignore type="button" wire:click="destroy" wire:confirm="Apakah anda yakin?" class="btn btn-icon btn-danger btn-xl"><i class='bx bx-trash bx-md'></i></button>
                        HTML);
                }),
            Button::add('create')
                ->render(function () {
                    return Blade::render(<<<HTML
                        <button wire:ignore.self wire:ignore type="button" class="btn btn-icon btn-primary btn-xl" data-bs-toggle="modal" data-bs-target="#CustomerCreateModal"><i class='bx bxs-plus-circle bx-md'></i></button>
                        HTML);
                }),
            // Button::add('customer-modal')
            //     ->id()
            //     ->slot('<span class="tf-icons bx bxs-plus-circle bx-sm"></span>')
            //     ->class('btn btn-icon btn-primary'),
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
        return Customer::with(["emails", "phones"])->select("customers.*");
    }

    public function relationSearch(): array
    {
        return ["emails" => ["id", "address", "verifiy_at"], "phones" => ["id", "number"]];
    }

    public function fields(): PowerGridFields
    {
        return PowerGrid::fields()
            ->add('name')
            ->add('pic_name')
            ->add('group')
            ->add('type')
            ->add('established_formatted', fn (Customer $model) => Carbon::parse($model->established)->format('d F Y'))
            ->add('website', fn (Customer $customer) => "<span class='text-wrap'>{$customer->website}</span>")
            ->add('emails', fn (Customer $customer) => $customer->emails->map(fn ($email) => $email->address)->join("<br>"))
            ->add('phones', fn (Customer $customer) => $customer->phones->map(fn ($phone) => $phone->number)->join("<br>"))
            ->add('created_at_formatted', fn (Customer $customer) => Carbon::parse($customer->created_at)->translatedFormat("d F Y"))
            ->add('updated_at_formatted', fn (Customer $customer) => Carbon::parse($customer->updated_at)->translatedFormat("d F Y"));
    }

    public function columns(): array
    {
        return [
            Column::make('Name', 'name')
                ->sortable()
                ->searchable(),

            Column::make('Pic name', 'pic_name')
                ->sortable()
                ->searchable(),

            Column::make('Group', 'group')
                ->sortable()
                ->searchable(),

            Column::make('Type', 'type')
                ->sortable()
                ->searchable(),

            Column::make('Established', 'established_formatted', 'established')
                ->sortable()->searchable(),

            Column::make('Website', 'website')
                ->sortable()
                ->searchable(),

            Column::add()->title("Emails")->field("emails", "customer_emails.address")->searchable(),
            Column::add()->title("Phones")->field("phones", "customer_phones.number")->searchable(),

            Column::make('Created at', 'created_at_formatted', 'created_at')
                ->sortable(),

            Column::make('Updated at', 'updated_at_formatted', 'updated_at')
                ->sortable()
                ->searchable(),

            Column::action('Action')
        ];
    }

    public function filters(): array
    {
        return [
            Filter::inputText('name', 'name'),
            Filter::inputText('pic_name', 'pic_name')
                ->operators(['contains']),
            Filter::inputText('group', 'group')
                ->operators(['contains']),
            Filter::multiSelect('type', 'type')
                ->dataSource(CustomerType::array())
                ->optionValue('id')
                ->optionLabel('name'),
            Filter::datepicker('established', 'established'),
            Filter::inputText('website', 'website')
                ->operators(['contains']),
            Filter::inputText('customer_emails.address', 'emails')
                ->operators(['contains'])->builder(function (Builder $q, mixed $keywords) {
                    $q->whereHas("emails", function ($q) use ($keywords) {
                        $q->where("address", "like", "%{$keywords['value']}%");
                    });
                }),
            Filter::inputText('customer_phones.number', 'emails')
                ->operators(['contains'])->builder(function (Builder $q, mixed $keywords) {
                    $q->whereHas("phones", function ($q) use ($keywords) {
                        $q->where("number", "like", "%{$keywords['value']}%");
                    });
                }),
            Filter::datepicker('created_at', 'created_at'),
            Filter::datepicker('updated_at', 'updated_at'),
        ];
    }

    #[\Livewire\Attributes\On('edit')]
    public function edit($rowId): void
    {
        $this->js('alert(' . $rowId . ')');
    }

    public function actions(Customer $row): array
    {
        return [
            // Button::add('custom')
            //     ->render(function () use ($row) {
            //         return Blade::render(<<<HTML
            //             <div>
            //                 <button type="button" class="badge badge-center bg-warning rounded-pill" wire:click="show('$row->id')"><span class="tf-icons bx bx-pencil"></span></button>
            //             </div>
            //             HTML);
            //     }),
            Button::add('edit')
                ->slot('<span class="tf-icons bx bx-pencil"></span>')
                ->class('badge badge-center bg-warning rounded-pill')
                ->dispatch('customer-show', ['customer' => $row->id]),
        ];
    }

    public function actionRules($row): array
    {
        return [
            // Rule::button('edit')->setAttribute("type", "button")->setAttribute('wire:ignore.self wire:ignore'),
        ];
    }

    public function show($customer)
    {
        $this->dispatch('customer-show', ['customer' => $customer]);
    }

    public function destroy()
    {
        if (empty($this->checkedValues())) {
            Toaster::error("Silahkan pilih data");
            return;
        }

        $errorMessage = "";

        foreach (Customer::whereIn("id", $this->checkedValues())->get() as $customer) {
            try {
                $customer->delete();
            } catch (\Throwable $th) {
                $errorMessage .= "Customer: {$customer->name} tidak dapat dihapus \n";
            }
        }

        $this->dispatch('$refresh');
        Toaster::success("Data berhasil di hapus \n {$errorMessage}");
    }
}
