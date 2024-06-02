<?php

namespace App\Models;

use App\Casts\NumberDisplayCast;
use App\Enums\InvoiceType;
use App\Observers\InvoiceObserver;
use Illuminate\Database\Eloquent\Attributes\ObservedBy;
use Illuminate\Database\Eloquent\Concerns\HasUuids;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use RichanFongdasen\EloquentBlameable\BlameableTrait;

#[ObservedBy([InvoiceObserver::class])]
class Invoice extends Model
{
    use HasFactory, HasUuids, BlameableTrait;

    protected $table = "invoices";
    protected $fillable = ["transaction_id", "number_display", "type", "subtotal", "total", "total_bill", "total_payment", "excess_payment", "tax", "stamp", "customer_name", "customer_pic_name", "customer_address", "customer_phone_number", "customer_email", "internal_note", "issue_date", "due_date", "is_tax"];

    protected function casts()
    {
        return [
            "number_display" => NumberDisplayCast::class,
        ];
    }

    public function transaction()
    {
        return $this->belongsTo(Transaction::class, "transaction_id", "id");
    }

    public function services()
    {
        return $this->hasMany(InvoiceService::class, "invoice_id", "id");
    }

    public function payments()
    {
        return $this->hasMany(Payment::class, "invoice_id", "id");
    }

    public function payment()
    {
        return $this->hasOne(Payment::class, "invoice_id", "id")->whereNotIn("status", ["cancel"])->orderByDesc("created_at");
    }

    public function paymentPaids()
    {
        return $this->hasMany(Payment::class, "invoice_id", "id")->where("status", "paid");
    }

    public function paymentCancels()
    {
        return $this->hasMany(Payment::class, "invoice_id", "id")->where("status", "cancel");
    }

    public function calculate()
    {
        $this->load(["services", "payments"]);
        $subtotal = $this->services->where("type", "beforeTax")->pluck("price")->sum();
        $tax = $this->is_tax ? $subtotal * ppn() : 0;
        $stamp = $this->stamp;
        $total = $subtotal + $tax + $stamp + $this->services->where("type", "afterTax")->pluck("price")->sum();
        $total_bill = $total - $this->paymentPaids->pluck("amount")->sum();

        $this->update([
            "subtotal" => $subtotal,
            "tax" => $tax,
            "stamp" => $stamp,
            "total" => $total,
            "total_bill" => $total_bill,
        ]);
    }

    public function createdBy()
    {
        return $this->belongsTo(User::class, "created_by", "id");
    }
}
