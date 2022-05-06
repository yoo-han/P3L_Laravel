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
        Schema::create('mitra_mobils', function (Blueprint $table) {
            $table->id('id_mitra');
            $table->string('nama_pemilik',64);
            $table->string('no_ktp_pemilik',16);
            $table->longtext('alamat_pemilik');
            $table->string('no_telp_pemilik',13);
            $table->dateTime('periode_kontrak_mulai');
            $table->dateTime('periode_kontrak_akhir');
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
        Schema::dropIfExists('mitra_mobils');
    }
};
