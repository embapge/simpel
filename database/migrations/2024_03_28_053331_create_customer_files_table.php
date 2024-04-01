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
        Schema::create('customer_files', function (Blueprint $table) {
            $table->uuid("id");
            $table->foreignUuid('customer_id')->nullable()->constrained("customers", "id")->cascadeOnUpdate()->restrictOnDelete();
            $table->foreignUuid('document_id')->nullable()->constrained("documents", "id")->cascadeOnUpdate()->restrictOnDelete();
            $table->string("name", 100);
            $table->integer("is_active")->default(1);
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
        Schema::dropIfExists('customer_files');
    }
};
