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
        Schema::create('customer_emails', function (Blueprint $table) {
            $table->uuid("id");
            $table->foreignUuid('customer_id')->nullable()->constrained("customers", "id")->cascadeOnUpdate()->restrictOnDelete();
            $table->enum("type", ["primary", "secondary"])->default("secondary");
            $table->dateTime("verify_at")->nullable();
            $table->timestamps();
            $table->softDeletes();
            $table->primary("id");
            $table->foreignUuid('created_by')->nullable()->constrained("users", "id")->cascadeOnUpdate()->restrictOnDelete();
            $table->foreignUuid('updated_by')->nullable()->constrained("users", "id")->cascadeOnUpdate()->restrictOnDelete();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('customer_emails');
    }
};
