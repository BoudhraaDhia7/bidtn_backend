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
        Schema::table('password_reset_tokens', function (Blueprint $table) {
            $table->unsignedBigInteger('created_at')->nullable()->default(null)->change();
            $table->unsignedBigInteger('deleted_at')->nullable()->default(null)->change();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('password_reset_tokens', function (Blueprint $table) {
            $table->unsignedBigInteger('created_at')->default(time())->change();
            $table->unsignedBigInteger('deleted_at')->default(time())->change();
        });
    }
};
