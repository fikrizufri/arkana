<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateKasBonsTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('kas_bon', function (Blueprint $table) {
            $table->uuid('id')->primary();
            $table->integer('uang')->default(0);
            $table->string('nota_kasbon')->nullable();
            $table->string('karyawan_id')->references('id')->on('karyawan');
            $table->string('user_id')->references('id')->on('user');
            $table->enum('status', ['belum', 'lunas'])->default('belum');
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
        Schema::dropIfExists('kas_bons');
    }
}
