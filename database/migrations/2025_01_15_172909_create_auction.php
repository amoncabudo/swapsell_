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
        Schema::create('auctions', function (Blueprint $table) {
            $table->id();
            $table->foreignId('product_id');
            $table->unsignedBigInteger('user_id');
            $table->unsignedBigInteger('last_bidder_id')->nullable();
            $table->decimal('start_price', total:8, places:2);
            $table->decimal('current_price', total:8, places:2);
            $table->dateTime('start_time');
            $table->dateTime('end_time');
            $table->boolean('status');
            $table->timestamps();

            $table->foreign('last_bidder_id')->references('id')->on('users')->onDelete('set null');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('auctions');
    }
};
