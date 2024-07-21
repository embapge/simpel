<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Concerns\HasUuids;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use RichanFongdasen\EloquentBlameable\BlameableTrait;

class InvoiceService extends Model
{
    use HasFactory, HasUuids, BlameableTrait;

    protected $table = "invoice_services";
    protected $fillable = ["invoice_id", "name", "type", "description", "price", "created_by"];

    public function invoice()
    {
        return $this->belongsTo(Invoice::class, "invoice_id", "id");
    }
}
