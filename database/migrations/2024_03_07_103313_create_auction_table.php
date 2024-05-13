<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('auctions', function (Blueprint $table) {
            $table->id();
            $table->string('title');
            $table->longText('description');
            $table->integer('starting_price');
            $table->unsignedBigInteger('user_id');
            $table->boolean('is_finished')->default(false);
            $table->boolean('is_confirmed')->default(false);
            $table->boolean('is_started')->default(false);
            $table->integer('current_bid')->default(0);
            $table->integer('starting_user_number')->default(7);
            $table->unsignedBigInteger('end_date')->nullable();
            $table->unsignedBigInteger('start_date')->default(time());
            $table->unsignedBigInteger('created_at')->default(time());
            $table->unsignedBigInteger('updated_at')->default(time());
            $table->index(['user_id']);
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('auctions');
    }
};