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
        Schema::create('medias', function (Blueprint $table) {
            $table->id();
            $table->string('model_type');
            $table->unsignedBigInteger('model_id');
            $table->string('file_name')->nullable();
            $table->string('file_type')->nullable();
            $table->string('file_path')->nullable();
            $table->unsignedBigInteger('created_at')->default(time());
            $table->unsignedBigInteger('updated_at')->default(time());
            $table->unsignedBigInteger('deleted_at')->nullable();

            $table->index(['model_type', 'model_id']);
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('media');
    }
};