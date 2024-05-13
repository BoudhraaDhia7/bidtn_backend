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
        Schema::create('transactions', function (Blueprint $table) {
            $table->id();
            $table->foreignId('user_id')->constrained()->onDelete('cascade');
            $table->foreignId('auction_id')->constrained()->onDelete('cascade');
            $table->double('amount', 8, 2);
            $table->enum('type', ['bid', 'refund']);
            $table->bigInteger('created_at');
            $table->bigInteger('updated_at');
            $table->softDeletesBigInteger();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('transaction');
    }
};
