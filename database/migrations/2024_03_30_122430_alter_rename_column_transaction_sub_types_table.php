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
        Schema::table('transaction_sub_types', function (Blueprint $table) {
            $table->renameColumn("transaction_id", "transaction_type_id");
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('transaction_sub_types', function (Blueprint $table) {
            $table->renameColumn("transaction_type_id", "transaction_id");
        });
    }
};
