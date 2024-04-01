<?php

use App\Enums\CustomerType;
use App\Models\Customer;
use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('invoices', function (Blueprint $table) {
            $table->uuid("id");
            $table->foreignUuid("transaction_id")->constrained("transactions", "id")->cascadeOnUpdate()->restrictOnDelete();
            $table->string("number_display", 20)->nullable();
            $table->enum("type", ["kw", "keu", "pro", "inv"])->nullable();
            $table->decimal("subtotal", 20, 0)->default(0);
            $table->decimal("total", 20, 0)->default(0);
            $table->decimal("total_bill", 20, 0)->default(0);
            $table->decimal("total_payment", 20, 0)->default(0);
            $table->decimal("excess_payment", 20, 0)->default(0);
            $table->decimal("tax", 20, 0)->default(0);
            $table->decimal("stamp", 20, 0)->default(0);
            $table->enum("status", ["draft", "unpaid", "paid", "lesspaid", "cancel"])->default("draft")->comment("Ini Adalah status pembayaran");
            $table->string("customer_name", 50);
            $table->string("customer_pic_name", 50)->nullable();
            $table->text("customer_address");
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
        Schema::dropIfExists('invoices');
    }
};
