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
        Schema::create('products', function (Blueprint $table) {
            $table->id('id_product');
            $table->string('nama_product');
            $table->string('description');
            $table->integer('stock');
            $table->integer('price');
            $table->string('image')->nullable();
            $table->unsignedBigInteger('id_category');
            $table->unsignedBigInteger('id_ukuran');
            $table->foreign('id_category')->references('id_category')->on('categories')->onDelete('cascade');
            $table->foreign('id_ukuran')->references('id_ukuran')->on('ukuran')->onDelete('cascade');
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('products');
    }
};
