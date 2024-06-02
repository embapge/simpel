<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('invoice_services', function (Blueprint $table) {
            $table->uuid("id");
            $table->foreignUuid("invoice_id")->constrained("invoices", "id")->cascadeOnUpdate()->cascadeOnDelete();
            $table->string("name", 50);
            $table->enum("type", ["beforeTax", "afterTax"])->default("beforeTax");
            $table->text("description")->nullable();
            $table->timestamps();
            $table->primary("id");
            $table->foreignUuid("created_by")->nullable()->constrained("users", "id")->cascadeOnUpdate()->restrictOnDelete();
            $table->foreignUuid("updated_by")->nullable()->constrained("users", "id")->cascadeOnUpdate()->restrictOnDelete();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('invoice_services');
    }
};
