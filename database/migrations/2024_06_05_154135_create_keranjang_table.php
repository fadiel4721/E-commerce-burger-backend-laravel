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
        Schema::create('keranjang', function (Blueprint $table) {
            $table->id('id_keranjang');
            $table->integer('qty')->default(0);
            $table->integer('harga_satuan')->default(0);
            $table->integer('total_harga')->default(0);
            $table->unsignedBigInteger('id_product');
            $table->unsignedBigInteger('id_customer');
            $table->unsignedBigInteger('id_checkout')->nullable();
            $table->foreign('id_product')->references('id_product')->on('products')->onDelete('cascade');
            $table->foreign('id_customer')->references('id')->on('users')->onDelete('cascade');
            $table->foreign('id_checkout')->references('id_checkout')->on('checkout')->onDelete('cascade');
            $table->timestamps();
        });
    
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('keranjang');
    }
};
