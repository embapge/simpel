<?php

namespace App\Models;

use App\Casts\TitleCast;
use Illuminate\Database\Eloquent\Concerns\HasUuids;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use RichanFongdasen\EloquentBlameable\BlameableTrait;

class TransactionSubType extends Model
{
    use HasFactory, HasUuids, BlameableTrait;
    protected $table = "transaction_sub_types";
    protected $fillable = ["transaction_id", "name", "description"];

    protected function casts()
    {
        return [
            "name" => TitleCast::class,
        ];
    }

    public function transactionType()
    {
        return $this->belongsTo(TransactionType::class, "transaction_sub_type_id");
    }

    public function documentTemplates()
    {
        return $this->belongsToMany(Document::class, "transaction_document_template_details", "transaction_sub_type_id", "document_id")->withPivot(["transaction_document_template_id", "transaction_sub_type_id", "document_id", "is_required"])->using(TransactionDocumentTemplateDetail::class);
    }

    public function transactionTemplates()
    {
        return $this->belongsToMany(TransactionDocumentTemplate::class, "transaction_document_template_details", "transaction_sub_type_id", "transaction_document_template_id")->withPivot(["transaction_document_template_id", "transaction_sub_type_id", "document_id"])->using(TransactionDocumentTemplateDetail::class);
    }
}
