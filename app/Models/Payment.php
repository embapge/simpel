<?php

namespace App\Models;

use Illuminate\Broadcasting\Channel;
use App\Enums\PaymentStatus;
use Illuminate\Database\Eloquent\BroadcastsEvents;
use Illuminate\Database\Eloquent\Concerns\HasUuids;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Notifications\Notifiable;
use RichanFongdasen\EloquentBlameable\BlameableTrait;

class Payment extends Model
{
    use Notifiable, HasFactory, HasUuids, BlameableTrait;

    protected $table = "payments";
    protected $fillable = ["invoice_id", "amount", "status"];
    protected $cast = [
        "status" => PaymentStatus::class
    ];

    public function invoice()
    {
        return $this->belongsTo(Invoice::class, "invoice_id", "id");
    }

    public function transaction()
    {
        return $this->hasOne(PaymentTransaction::class, "payment_id", "id")->orderByDesc("id");
    }
    public function transactions()
    {
        return $this->hasMany(PaymentTransaction::class, "payment_id", "id");
    }

    public function createdBy()
    {
        return $this->belongsTo(User::class, "created_by", "id");
    }

    public function updatedBy()
    {
        return $this->belongsTo(User::class, "updated_by", "id");
    }
}
