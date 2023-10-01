<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreatePembelianProdukTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('pembelian_produk', function (Blueprint $table) {
            //FOREIGN KEY CONSTRAINTS
            $table->string('produk_id')->references('id')->on('produk')->onDelete('cascade');
            $table->string('pembelian_id')->references('id')->on('pembelian')->onDelete('cascade');
            $table->integer('qty')->default(0);
            $table->integer('harga_beli')->default(0);

            //SETTING THE PRIMARY KEYS
            $table->primary(['produk_id', 'pembelian_id']);
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('pembelian_produk');
    }
}
