<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    /**
     * Run the migrations.
     */
    public function up(): void
    {
        Schema::create('transaction_document_template_details', function (Blueprint $table) {
            $table->foreignUuid("transaction_document_template_id")->constrained("transaction_document_templates", "id", "transaction_document_template_transaction_document_detail_fk")->cascadeOnUpdate()->restrictOnDelete();
            $table->foreignUuid("transaction_sub_type_id")->constrained("transaction_sub_types", "id", "transaction_document_template_transaction_sub_type_fk")->cascadeOnUpdate()->restrictOnDelete();
            $table->foreignUuid("document_id")->constrained("documents", "id");
            $table->timestamps();
            $table->foreignUuid('created_by')->nullable()->constrained("users", "id")->cascadeOnUpdate()->restrictOnDelete();
            $table->foreignUuid('updated_by')->nullable()->constrained("users", "id")->cascadeOnUpdate()->restrictOnDelete();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('transaction_document_template_details');
    }
};
