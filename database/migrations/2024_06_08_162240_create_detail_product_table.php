<?php
use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateDetailProductTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('detail_product', function (Blueprint $table) {
            $table->id('id_detail_product');
            $table->unsignedBigInteger('id_product');
            $table->unsignedBigInteger('id_category');
            $table->unsignedBigInteger('id_ukuran');
            $table->foreign('id_product')->references('id_product')->on('products')->onDelete('cascade');
            $table->foreign('id_category')->references('id_category')->on('categories')->onDelete('cascade');
            $table->foreign('id_ukuran')->references('id_ukuran')->on('ukuran')->onDelete('cascade');
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('detail_product');
    }
}
