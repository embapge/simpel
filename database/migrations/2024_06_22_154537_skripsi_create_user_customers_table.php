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
        Schema::create('user_customers', function (Blueprint $table) {
            $table->foreignUuid("user_id")->constrained("users", "id")->cascadeOnUpdate()->restrictOnDelete();
            $table->foreignUuid("customer_id")->constrained("customers", "id")->cascadeOnUpdate()->restrictOnDelete();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('user_customers');
    }
};
