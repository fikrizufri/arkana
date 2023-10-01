<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreatePembayaranKasBonsTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('pembayaran_kas_bon', function (Blueprint $table) {
            $table->uuid('id')->primary();
            $table->string('nota_pembayaran_kasbon')->nullable();
            $table->string('kas_bon_id')->references('id')->on('kas_bon');
            $table->string('user_id')->references('id')->on('user');
            $table->integer('uang')->default(0);
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
        Schema::dropIfExists('pembayaran_kas_bons');
    }
}
