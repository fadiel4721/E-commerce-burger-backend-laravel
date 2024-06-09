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
        Schema::create('checkout', function (Blueprint $table) {
            $table->id('id_checkout');
            $table->string('metode_kirim'); 
            $table->string('metode_bayar');
            $table->integer('biaya_kirim')->default(0);
            $table->integer('total_pembayaran')->default(0);
            $table->string('alamat');
            $table->unsignedBigInteger('id_customer');
            $table->foreign('id_customer')->references('id')->on('users')->onDelete('cascade');
            $table->timestamps();
        });
   
  
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
      
        Schema::dropIfExists('checkout');
    }
};
