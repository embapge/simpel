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
        Schema::create('payments', function (Blueprint $table) {
            $table->uuid("id");
            $table->foreignUuid("invoice_id")->constrained("invoices", "id")->cascadeOnUpdate()->restrictOnDelete();
            $table->foreignUuid("bank_account_id")->constrained("bank_accounts", "id")->cascadeOnUpdate()->restrictOnDelete();
            $table->decimal("amount", 20, 2)->default(0);
            $table->enum("status", ["paid", "cancel"]);
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
        Schema::dropIfExists('payments');
    }
};
