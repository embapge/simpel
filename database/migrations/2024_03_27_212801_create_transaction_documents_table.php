<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('transaction_documents', function (Blueprint $table) {
            $table->foreignUuid("transaction_id")->constrained("transactions", "id");
            $table->foreignUuid("document_id")->constrained("documents", "id");
            $table->dateTime("date")->nullable();
            $table->string("file", 50)->nullable();
            $table->foreignUuid("created_by")->nullable()->constrained("users", "id");
            $table->foreignUuid("updated_by")->nullable()->constrained("users", "id");
            $table->timestamps();
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('transaction_documents');
    }
};
