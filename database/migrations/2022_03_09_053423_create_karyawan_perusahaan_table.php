<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateKaryawanPerusahaanTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('karyawan_perusahaan', function (Blueprint $table) {
            //FOREIGN KEY CONSTRAINTS
            $table->string('modul_id');
            $table->string('karyawan_id')->references('id')->on('karyawan')->onDelete('cascade');
            $table->string('modul');

            //SETTING THE PRIMARY KEYS
            $table->primary(['modul_id', 'karyawan_id']);
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('karyawan_perusahaan');
    }
}
