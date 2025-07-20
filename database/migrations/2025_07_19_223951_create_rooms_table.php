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
        Schema::create('rooms', function (Blueprint $table) {
            $table->id();
            $table->unsignedBigInteger('hotel_id');
            $table->foreign('hotel_id')->references('id')->on('hotels')->onDelete('cascade');
            $table->string('room_type');
            $table->text('description')->nullable();
            $table->integer('capacity');
            $table->integer('max_adults');
            $table->integer('max_children')->default(0);
            $table->decimal('base_price', 10, 2);
            $table->integer('size_sqm')->nullable();
            $table->string('bed_type')->nullable();
            $table->integer('quantity_available')->default(1);
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('rooms');
    }
};
