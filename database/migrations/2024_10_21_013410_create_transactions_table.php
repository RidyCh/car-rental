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
            $table->string('nik')->unique();
            $table->foreign('nik')->references('nik')->on('users')->cascadeOnDelete();
            $table->foreignId('car_id')->constrained('cars')->cascadeOnDelete();
            $table->integer('amount_car')->unsigned();
            $table->date('rental_date');
            $table->time('pick_up_at');
            $table->integer('duration')->unsigned();
            $table->boolean('driver')->default(false);
            $table->decimal('price_total', 10, 2);
            $table->decimal('dp', 10, 2);
            $table->decimal('price_final', 10, 2);
            $table->enum('status', ['Booked', 'On Rent', 'Returned', 'Cancelled'])->default('Booked');
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('transactions');
    }
};