<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up()
    {
        Schema::create('products', function (Blueprint $table) {
            $table->id();
            $table->string('name');
            $table->text('description');
            $table->unsignedBigInteger('user_id');
            $table->unsignedBigInteger('auction_id')->nullable();
            $table->unsignedBigInteger('created_at')->default(time());
            $table->unsignedBigInteger('updated_at')->default(time());
            $table->index(['user_id', 'auction_id']);
        });
    }

    public function down()
    {
        Schema::dropIfExists('products');
    }
};
