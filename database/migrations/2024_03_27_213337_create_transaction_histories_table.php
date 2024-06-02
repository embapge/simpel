<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('transaction_histories', function (Blueprint $table) {
            $table->uuid("id");
            $table->foreignUuid("transaction_id")->constrained("transactions", "id")->cascadeOnUpdate()->cascadeOnDelete();
            $table->dateTime("date");
            $table->enum("status", ["verification", "progress", "done", "cancel"]);
            $table->text("description");
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
        Schema::dropIfExists('transaction_histories');
    }
};
