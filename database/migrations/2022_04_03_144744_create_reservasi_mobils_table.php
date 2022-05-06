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
        Schema::create('reservasi_mobils', function (Blueprint $table) {
            $table->string('id_reservasi')->primary();
            $table->string('id_customer');
            $table->foreign('id_customer')->references('id_customer')->on('customers')->onDelete('cascade');
            $table->unsignedBigInteger('id_mobil')->index();
            $table->foreign('id_mobil')->references('id_mobil')->on('mobils')->onDelete('cascade');    
            $table->unsignedBigInteger('id_pegawai')->index();
            $table->foreign('id_pegawai')->references('id_pegawai')->on('pegawais')->onDelete('cascade');
            $table->unsignedBigInteger('id_promo')->index()->nullable();
            $table->foreign('id_promo')->references('id_promo')->on('promos')->onDelete('cascade');
            $table->string('id_driver')->nullable();
            $table->foreign('id_driver')->references('id_driver')->on('drivers')->onDelete('cascade');
            $table->dateTime('tanggal_transaksi');
            $table->dateTime('tanggal_mulai');
            $table->dateTime('tanggal_selesai');
            $table->string('jenis_reservasi');
            $table->string('no_sim',12)->nullable();
            $table->float('tarif_driver',12)->nullable();
            $table->string('metode_pembayaran',64);
            $table->float('total_pembayaran',12);
            $table->string('bukti_transfer');
            $table->dateTime('tanggal_kembali')->nullable();
            $table->float('denda',12)->nullable();
            $table->smallInteger('rating_driver')->nullable();
            $table->string('status_reservasi',50)->default('Belum Bayar Belum Verifikasi');
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
        Schema::dropIfExists('reservasi_mobils');
    }
};
