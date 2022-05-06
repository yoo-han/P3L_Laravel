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
        Schema::create('mobils', function (Blueprint $table) {
            $table->id('id_mobil');
            $table->bigInteger('id_mitra')->unsigned()->index()->nullable();
            $table->foreign('id_mitra')->references('id_mitra')->on('mitra_mobils')->onDelete('cascade');
            $table->string('nama_mobil',64);
            $table->string('tipe_mobil',64);
            $table->string('jenis_transmisi',64);
            $table->string('jenis_bahan_bakar',64);
            $table->float('volume_bahan_bakar',5);
            $table->string('warna_mobil',64);
            $table->smallInteger('kapasitas_penumpang');
            $table->string('fasilitas',64);
            $table->string('kategori_aset',64);
            $table->string('plat_nomor',10);
            $table->string('nomor_stnk');
            $table->float('harga_sewa');
            $table->string('foto_mobil');
            $table->bigInteger('total_peminjaman')->default(0);
            $table->date('tanggal_terakhir_servis')->nullable();
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
        Schema::dropIfExists('mobils');
    }
};
