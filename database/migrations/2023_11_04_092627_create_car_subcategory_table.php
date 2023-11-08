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
        Schema::create('car_subcategory', function (Blueprint $table) {
            $table->id();
            $table->foreignId('car_id')
                ->nullable()
                ->references('id')->on('cars')
                ->onDelete('cascade')
                ->onUpdate('cascade');
            $table->foreignId('subcategory_id')
                ->nullable()
                ->references('id')->on('subcategories')
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
        Schema::dropIfExists('car_subcategory');
    }
};
