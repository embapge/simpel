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
            $table->string("customer_email", 50)->nullable();
            $table->string("customer_phone_number", 50)->nullable();
            $table->timestamp("issue_date");
            $table->timestamp("due_date")->nullable();
            $table->integer("is_tax")->default(1);
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('invoices', function (Blueprint $table) {
            $table->dropColumn(["customer_email", "customer_phone_number", "issue_date", "due_date", "is_tax"]);
        });
    }
};
