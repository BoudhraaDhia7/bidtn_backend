<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration {
    /**
     * Run the migrations.
     */
    public function up(): void
    {
        Schema::create('auction_participants', function (Blueprint $table) {
            $table->id();
            $table->unsignedBigInteger('auction_id');
            $table->unsignedBigInteger('user_id');
            $table->integer('paid_amount')->default(0);
            $table->boolean('should_refund')->default(0);
            $table->boolean('is_refunded')->default(false);
            $table->foreign('auction_id')->references('id')->on('auctions');
            $table->foreign('user_id')->references('id')->on('users');
            $table->unsignedBigInteger('created_at')->default(time());
            $table->unsignedBigInteger('updated_at')->default(time());
            $table->unique(['auction_id', 'user_id']);
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('jeton_transaction');
    }
};
