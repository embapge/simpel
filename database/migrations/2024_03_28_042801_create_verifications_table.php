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
        Schema::create('verifications', function (Blueprint $table) {
            $table->uuid("id");
            $table->foreignUuid("transaction_sub_type_id")->constrained("transaction_sub_types", "id")->cascadeOnUpdate()->restrictOnDelete();
            $table->string("name", 50);
            $table->string("pic_name", 50);
            $table->text("address");
            $table->string("email", 50)->unique();
            $table->dateTime("verify_at")->nullable();
            $table->string("phone_number", 50)->unique();
            $table->text("website")->nullable();
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
        Schema::dropIfExists('verifications');
        DB::statement('SET FOREIGN_KEY_CHECKS = 1');
    }
};
