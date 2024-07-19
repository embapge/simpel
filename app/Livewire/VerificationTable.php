<?php

namespace App\Livewire;

use App\Livewire\Forms\VerificationForm;
use App\Models\Verification;
use App\Models\Transaction;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Support\Carbon;
use Illuminate\Support\Facades\Blade;
use Illuminate\Support\Facades\DB;
use Livewire\Attributes\On;
use Masmerise\Toaster\Toast;
use Masmerise\Toaster\Toaster;
use PowerComponents\LivewirePowerGrid\Button;
use PowerComponents\LivewirePowerGrid\Column;
use PowerComponents\LivewirePowerGrid\Exportable;
use PowerComponents\LivewirePowerGrid\Facades\Filter;
use PowerComponents\LivewirePowerGrid\Facades\Rule;
use PowerComponents\LivewirePowerGrid\Footer;
use PowerComponents\LivewirePowerGrid\Header;
use PowerComponents\LivewirePowerGrid\PowerGrid;
use PowerComponents\LivewirePowerGrid\PowerGridFields;
use PowerComponents\LivewirePowerGrid\PowerGridComponent;
use PowerComponents\LivewirePowerGrid\Traits\WithExport;

final class VerificationTable extends PowerGridComponent
{
    protected $listeners = ['verificationRefreshTable' => '$refresh'];

    use WithExport;

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
        return Verification::with(["documents"])->select("verifications.*");
        // return DB::table('verifications')->leftJoin("verification_documents", "verifications.id", "=", "verification_documents.verification_id")->select("verifications.id");
    }

    public function relationSearch(): array
    {
        return [
            // "customer" => ["name"]
        ];
    }

    public function fields(): PowerGridFields
    {
        return PowerGrid::fields()
            ->add('id')
            ->add('transaction_sub_type_id')
            ->add('name')
            ->add('pic_name')
            ->add('address')
            ->add('email')
            ->add('verify_at_formatted', fn ($model) => $model->verify_at ? Carbon::parse($model->verify_at)->translatedFormat('d F Y H:i:s A') : "Belum diverifikasi")
            ->add('phone_number')
            ->add('website')
            ->add('created_at_formatted', fn ($model) => $model->created_at ? Carbon::parse($model->created_at)->translatedFormat('d F Y H:i:s A') : "")
            ->add('updated_at_formatted', fn ($model) => $model->updated_at ? Carbon::parse($model->updated_at)->translatedFormat('d F Y H:i:s A') : "")
            ->add('created_by')
            ->add('updated_by')
            ->add('status');
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

            Column::make('Address', 'address')
                ->sortable()
                ->searchable(),

            Column::make('Email', 'email')
                ->sortable()
                ->searchable(),

            Column::make('Verify at', 'verify_at_formatted', 'verify_at')
                ->sortable(),

            Column::make('Phone number', 'phone_number')
                ->sortable()
                ->searchable(),

            Column::make('Website', 'website')
                ->sortable()
                ->searchable(),

            Column::make('Created at', 'created_at_formatted', 'created_at')
                ->sortable()->searchable(),

            Column::make('Updated at', 'updated_at_formatted', 'updated_at')
                ->sortable()->searchable(),

            Column::make('Created by', 'created_by')
                ->sortable()
                ->searchable(),

            Column::make('Updated by', 'updated_by')
                ->sortable()
                ->searchable(),

            Column::make('Status', 'status')
                ->sortable()
                ->searchable(),

            Column::action('Action')
        ];
    }

    public function filters(): array
    {
        return [
            Filter::inputText("name")->operators(["contains"]),
            Filter::inputText("pic_name")->operators(["contains"]),
            Filter::inputText("address")->operators(["contains"]),
            Filter::inputText("email")->operators(["contains"]),
            Filter::inputText("phone_number")->operators(["contains"]),
            Filter::inputText("website")->operators(["contains"]),
            Filter::inputText("created_by")->operators(["contains"]),
            Filter::inputText("updated_by")->operators(["contains"]),
            Filter::datepicker('verify_at'),
            Filter::datepicker('created_at'),
            Filter::datepicker('updated_at'),
        ];
    }

    #[\Livewire\Attributes\On('edit')]
    public function edit($rowId): void
    {
        $this->js('alert(' . $rowId . ')');
    }

    public function actions($row): array
    {
        return [
            Button::add('show-document')
                ->slot("<i class='bx bx-file'></i>") // Display
                ->id()
                ->class('badge badge-center bg-info rounded-pill')
                ->dispatch('verification-show', ['verification' => $row->id]),
            Button::add('show-resend-link')
                ->slot("<i class='bx bx-mail-send'></i>") // Display
                ->id()
                ->class('badge badge-center bg-danger rounded-pill')
                ->dispatch('show-resend-link', ['verification' => $row->id]),
            Button::add('verification-succed')
                ->render(function () use ($row) {
                    return Blade::render(<<<HTML
                        <div wire:key="$row->id" wire:ignore>
                            <button type="button" class="badge badge-center bg-success rounded-pill" wire:confirm="Apakah anda yakin data tersebut sudah benar?" wire:click="verificationSucced('$row->id')"><i class="tf-icons bx bx-check-double"></i></button>
                        </div>
                        HTML);
                }),
        ];
    }

    public function actionRules($row): array
    {
        return [
            Rule::button("show-resend-link")
                ->when(fn ($row) => $row->documents->where("is_verified", 0)->isEmpty())
                ->hide(),
            Rule::button('verification-succed')
                ->when(fn ($row) => $row->documents->where("is_verified", 1)->count() < $row->documents->count())
                ->when(fn ($row) => $row->status != "success")
                ->hide(),
        ];
    }

    public function verificationSucced(Verification $verification)
    {
        try {
            $form = new VerificationForm($this, "verificationForm");
            $form->setVerification($verification);
            $transaction = $form->succed();
            if ($transaction instanceof Transaction) {
                $this->dispatch("verificationRefreshTable");
                Toaster::success("Service berhasil dibuat");
                $this->redirectRoute("transaction.detail", ["transaction" => $transaction->id]);
            }
            Toaster::error("Pelanggan sudah terdaftar");
        } catch (\Throwable $th) {
            Toaster::error($th->getMessage());
        }
    }
}
