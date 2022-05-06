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
        Schema::create('drivers', function (Blueprint $table) {
            $table->string('id_driver')->primary();
            $table->string('nama_driver',64);
            $table->longText('alamat_driver');
            $table->date('tanggal_lahir_driver');
            $table->string('jenis_kelamin_driver');
            $table->string('email_driver');
            $table->string('password_driver');
            $table->string('no_telp_driver',13);
            $table->string('bahasa',32);
            $table->string('sim_driver');
            $table->string('surat_bebas_napza');
            $table->string('surat_kesehatan_jasmani');
            $table->string('surat_kesehatan_jiwa');
            $table->string('skck');
            $table->string('status_driver',15)->default('Unverified');
            $table->float('rerata_rating',5)->default(0);
            $table->bigInteger('banyak_rating')->default(0);
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
        Schema::dropIfExists('drivers');
    }
};
