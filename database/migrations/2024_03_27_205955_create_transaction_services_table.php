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
        Schema::create('transaction_services', function (Blueprint $table) {
            $table->uuid("id");
            $table->foreignUuid("transaction_id")->constrained("transactions", "id")->cascadeOnUpdate()->cascadeOnDelete();
            $table->string("name", 20);
            $table->decimal("price", 20, 2)->default(0);
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
        Schema::dropIfExists('transaction_services');
    }
};
