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
        Schema::create('customers', function (Blueprint $table) {
            $table->uuid("id");
            $table->string("name", 50);
            $table->string("pic_name", 50);
            $table->text("address");
            $table->string("email", 50)->unique();
            $table->dateTime("verify_at")->nullable();
            $table->string("phone_number", 50)->unique();
            $table->text("website")->nullable();
            $table->timestamps();
            $table->foreignUuid('created_by')->nullable()->constrained("users", "id")->cascadeOnUpdate()->restrictOnDelete();
            $table->foreignUuid('updated_by')->nullable()->constrained("users", "id")->cascadeOnUpdate()->restrictOnDelete();
            $table->primary("id");
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('customers');
    }
};
