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
        Schema::create('pegawais', function (Blueprint $table) {
            $table->id('id_pegawai');
            $table->string('nama_pegawai',64);
            $table->longtext('alamat_pegawai');
            $table->date('tanggal_lahir_pegawai');
            $table->string('jenis_kelamin_pegawai',9);
            $table->string('email_pegawai');
            $table->string('password_pegawai');
            $table->string('no_telp_pegawai',13);
            $table->string('foto_pegawai');
            $table->string('jabatan_pegawai',20);
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
        Schema::dropIfExists('pegawais');
    }
};
