<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('detail_shifts', function (Blueprint $table) {
            $table->id('id_detail_shift');
            $table->unsignedBigInteger('id_pegawai')->index();
            $table->foreign('id_pegawai')->references('id_pegawai')->on('pegawais')->onDelete('cascade');
            $table->unsignedBigInteger('id_shift')->index();
            $table->foreign('id_shift')->references('id_shift')->on('shifts')->onDelete('cascade');
            $table->string('hari',6);
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
        Schema::dropIfExists('detail_shifts');
    }
};
