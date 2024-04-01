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
            $table->foreignUuid("transaction_id")->constrained("transactions", "id");
            $table->dateTime("date");
            $table->enum("status", ["verification", "progress", "done", "cancel"]);
            $table->text("description");
            $table->timestamps();
            $table->primary("id");
            $table->foreignUuid("created_by")->nullable()->constrained("users", "id");
            $table->foreignUuid("updated_by")->nullable()->constrained("users", "id");
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
