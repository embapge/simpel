<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    /**
     * Run the migrations.
     */
    public function up(): void
    {
        Schema::create('transactions', function (Blueprint $table) {
            $table->uuid("id");
            $table->foreignUuid("customer_id")->constrained("customers", "id")->cascadeOnUpdate()->restrictOnDelete();
            $table->foreignUuid("transaction_sub_type_id")->constrained("transaction_sub_types", "id")->cascadeOnUpdate()->restrictOnDelete();
            $table->string("number_display", 20)->nullable();
            $table->enum("sub_type", ["kk", "ppmkk", "ppkk", "kp", "rbsgmk"]);
            $table->decimal("total_bill", 20, 2)->default(0);
            $table->decimal("total", 20, 2)->default(0);
            $table->decimal("total_payment", 20, 2)->default(0);
            $table->decimal("excess_payment", 20, 2)->default(0);
            $table->enum("status", ["draft", "unpaid", "paid", "lesspaid", "cancel"])->default("draft")->comment("Ini Adalah status pembayaran");
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
        DB::statement('SET FOREIGN_KEY_CHECKS = 0');
        Schema::dropIfExists('transactions');
        DB::statement('SET FOREIGN_KEY_CHECKS = 1');
    }
};
