<?php

namespace App\Models;

use App\Casts\NumberDisplayCast;
use App\Casts\StatusCast;
use App\Casts\UangCast;
use App\Enums\TransactionStatus;
use App\Observers\TransactionObserver;
use Database\Factories\TransactionFactory;
use Illuminate\Database\Eloquent\Attributes\ObservedBy;
use Illuminate\Database\Eloquent\Concerns\HasUuids;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use RichanFongdasen\EloquentBlameable\BlameableTrait;
use Illuminate\Database\Eloquent\Factories\Factory;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

#[ObservedBy([TransactionObserver::class])]
class Transaction extends Model
{
    use HasFactory, HasUuids, BlameableTrait;

    protected $table = "transactions";
    protected $fillable = ["customer_id", "number_display", "type", "transaction_sub_type_id", "total_bill", "total", "total_payment", "excess_payment", "status", "internal_note", "created_at", "created_by"];

    protected function casts()
    {
        return [
            "status" => StatusCast::class,
            "number_display" => NumberDisplayCast::class,
        ];
    }

    protected static function newFactory(): Factory
    {
        return TransactionFactory::new();
    }

    public function subType()
    {
        return $this->belongsTo(TransactionSubType::class, "transaction_sub_type_id", "id");
    }

    public function customer()
    {
        return $this->belongsTo(Customer::class, "customer_id", "id");
    }

    public function services()
    {
        return $this->hasMany(TransactionService::class, "transaction_id", "id");
    }

    public function invoices()
    {
        return $this->hasMany(Invoice::class, "transaction_id", "id");
    }

    public function documents()
    {
        return $this->belongsToMany(Document::class, "transaction_documents", "transaction_id", "document_id")->withPivot(["created_by", "updated_by", "date", "file"])->withTimestamps()->using(TransactionDocument::class);
    }

    public function histories()
    {
        return $this->hasMany(TransactionHistory::class, "transaction_id", "id");
    }

    public function recentHistory()
    {
        return $this->hasOne(TransactionHistory::class, "transaction_id", "id")->latest()->first();
    }

    public function calculate()
    {
        $this->refresh();

        $this->load(["services", "invoices" => function ($q) {
            $q->select("id", "transaction_id", "subtotal", "total", "total_bill", "total_payment", "excess_payment");
            $q->with("paymentPaids:id,invoice_id,amount");
        }]);

        if ($this->number_display != "DRAFT") {
            $totalBill = $this->services->sum("price") - $this->invoices->pluck("total_payment")->sum() < 0 ? 0 : $this->services->sum("price") - $this->invoices->pluck("total_payment")->sum();
            if ($totalBill == 0) {
                $status = TransactionStatus::PAID;
            } else if ($totalBill > 0 && $totalBill < $this->services->sum("price")) {
                $status = TransactionStatus::LESSPAID;
            } else {
                $status = TransactionStatus::UNPAID;
            }
        } else {
            $status = TransactionStatus::DRAFT;
        }

        // dd($this->services->pluck("price")->sum());

        $this->update([
            "total" => $this->services->sum("price"),
            "total_bill" => $this->invoices->pluck("total_bill")->sum(),
            "total_payment" => $this->invoices->pluck("total_payment")->sum(),
            "excess_payment" => $this->invoices->pluck("excess_payment")->sum(),
            "status" => $status
        ]);

        return $this;
    }
}
