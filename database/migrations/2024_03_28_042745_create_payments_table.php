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
            $table->enum("bank", ["bca", "bri", "mandiri", "permata", "bni", "cimb niaga"])->nullable();
            $table->decimal("amount", 20, 2)->default(0);
            $table->timestamp("issue_date")->nullable();
            $table->timestamp("due_date")->nullable();
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
