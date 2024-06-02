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
        Schema::create('verification_documents', function (Blueprint $table) {
            $table->uuid("id");
            $table->foreignUuid('verification_id')->constrained("verifications", "id")->cascadeOnUpdate()->cascadeOnDelete();
            $table->foreignUuid('document_id')->constrained("documents", "id")->cascadeOnUpdate()->restrictOnDelete();
            $table->dateTime("date")->nullable();
            $table->string("file", 50)->nullable();
            $table->timestamps();
            $table->primary("id");
            $table->foreignUuid('created_by')->nullable()->constrained("users", "id")->cascadeOnUpdate()->restrictOnDelete();
            $table->foreignUuid('updated_by')->nullable()->constrained("users", "id")->cascadeOnUpdate()->restrictOnDelete();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('verification_documents');
    }
};
