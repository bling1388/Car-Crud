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
        Schema::create('cart_items', function (Blueprint $table) {
            $table->id();
            $table->integer('quantity');
            $table->string('brand')->nullable();
            $table->string('model')->nullable();
            $table->date('reg_date')->nullable();
            $table->string('eng_size')->nullable();
            $table->double('price')->nullable();
            $table->string('photo')->nullable();
            $table->json('tags')->nullable();
            $table->foreignId('user_id')
                ->nullable()
                ->references('id')->on('users')
                ->onDelete('cascade')
                ->onUpdate('cascade');
            $table->foreignId('car_id')
                ->nullable()
                ->references('id')->on('cars')
                ->onDelete('cascade')
                ->onUpdate('cascade');
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('cart_items');
    }
};
