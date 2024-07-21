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
use Illuminate\Support\Str;

final class CustomerTable extends PowerGridComponent
{
    public bool $deferLoading = true;
    protected $listeners = ['customerRefreshTable' => '$refresh'];
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
                        <button wire:ignore.self wire:ignore type="button" class="btn btn-icon btn-primary btn-md mt-2" data-bs-toggle="modal" data-bs-target="#CustomerCreateModal"><i class='bx bxs-plus-circle'></i></button>
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
        return Customer::with(["emails", "phones", "transactions:id,customer_id,total_payment"])->select("customers.*");
    }

    public function relationSearch(): array
    {
        return ["emails" => ["id", "address", "verifiy_at"], "phones" => ["id", "number"]];
    }

    public function fields(): PowerGridFields
    {
        return PowerGrid::fields()
            ->add('name', fn (Customer $customer) => "<span class='badge badge-center rounded-pill bg-label-primary'>" . Str::upper($customer->name[0]) . "</span> {$customer->name}")
            ->add('pic_name', fn (Customer $customer) => "<span class='badge badge-center rounded-pill bg-label-primary'>" . Str::upper($customer->pic_name[0]) . "</span> {$customer->pic_name}")
            ->add("email")
            ->add("phone_number")
            ->add("address", fn (Customer $customer) => "<span class='text-wrap'>{$customer->address}</span>")
            ->add('website', fn (Customer $customer) => "<span class='text-wrap'>{$customer->website}</span>")
            ->add('emails', fn (Customer $customer)  => $customer->emails->sortBy("type")->map(fn ($email) => $email->address)->join("<br>"))
            ->add('phones', fn (Customer $customer) => $customer->phones->sortBy("type")->map(fn ($phone) => $phone->number)->join("<br>"))
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

            Column::make('Email', 'email')
                ->sortable()
                ->searchable(),

            Column::make('Nomor Telepon', 'phone_number')
                ->sortable()
                ->searchable(),

            Column::make('Address', 'address')
                ->sortable()
                ->searchable(),

            Column::add()->title("Kontak Email")->field("emails", "customer_emails.address")->searchable(),

            Column::add()->title("Kontak Telepon")->field("phones", "customer_phones.number")->searchable(),

            Column::make('Website', 'website')
                ->sortable()
                ->searchable(),

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
            // Filter::multiSelect('type', 'type')
            //     ->dataSource(CustomerType::array())
            //     ->optionValue('id')
            //     ->optionLabel('name'),
            Filter::inputText('email', 'email')
                ->operators(['contains']),
            Filter::inputText('phone_number', 'phone_number')
                ->operators(['contains']),
            Filter::inputText('address', 'address')
                ->operators(['contains']),
            Filter::inputText('website', 'website')
                ->operators(['contains']),
            Filter::inputText('customer_emails.address', 'emails')
                ->operators(['contains'])->builder(function (Builder $q, mixed $keywords) {
                    $q->whereHas("emails", function ($q) use ($keywords) {
                        $q->where("address", "like", "%{$keywords['value']}%");
                    });
                }),
            Filter::inputText('customer_phones.number', 'phones')
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
            Button::add('customer-show')
                ->slot("<i class='bx bx-pencil'></i>") // Display
                ->id()
                ->class('badge badge-center bg-warning rounded-pill')
                ->dispatch('customer-show', ['customer' => $row->id]),

            Button::add('customer-user-access')
                ->slot("<i class='bx bx-user'></i>") // Display
                ->id("customer-user")
                ->class('badge badge-center bg-info rounded-pill')
                ->dispatch('customer-user-access', ['customer' => $row->id]),
        ];
    }

    public function actionRules($row): array
    {
        return [
            Rule::button('customer-user-access')->setAttribute("wire:confirm='Apakah kamu yakin akan membuatkan pelanggan ini hak akses aplikasi?'")->when(fn ($row) => $row->transactions->pluck("total_payment")->sum() <= 0)->hide(),
        ];
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

        $this->dispatch('customerResetForm');
        $this->dispatch('$refresh');
        Toaster::success("Data berhasil di hapus \n {$errorMessage}");
    }
}
