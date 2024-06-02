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
        Schema::table('invoices', function (Blueprint $table) {
            $table->decimal("subtotal", 20, 2)->default(0)->change();
            $table->decimal("total", 20, 2)->default(0)->change();
            $table->decimal("total_bill", 20, 2)->default(0)->change();
            $table->decimal("total_payment", 20, 2)->default(0)->change();
            $table->decimal("excess_payment", 20, 2)->default(0)->change();
            $table->decimal("tax", 20, 2)->default(0)->change();
            $table->decimal("stamp", 20, 2)->default(0)->change();
        });

        Schema::table('invoice_services', function (Blueprint $table) {
            $table->decimal("price", 20, 2)->default(0);
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('invoice_services', function (Blueprint $table) {
            //
        });
    }
};
