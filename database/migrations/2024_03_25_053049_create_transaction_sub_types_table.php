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
        Schema::create('transaction_sub_types', function (Blueprint $table) {
            $table->uuid("id");
            $table->foreignUuid("transaction_id")->constrained("transaction_types", "id")->cascadeOnUpdate()->restrictOnDelete();
            $table->text("name");
            $table->text("description")->nullable();
            $table->timestamps();
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
        DB::statement('SET FOREIGN_KEY_CHECKS = 0');
        Schema::dropIfExists('transaction_sub_types');
        DB::statement('SET FOREIGN_KEY_CHECKS = 1');
    }
};
