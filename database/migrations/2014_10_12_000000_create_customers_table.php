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
        Schema::create('customers', function (Blueprint $table) {
            $table->string('id_customer')->primary();
            $table->string('nama_customer',64);
            $table->longtext('alamat_customer');
            $table->date('tanggal_lahir_customer');
            $table->string('jenis_kelamin_customer',9);
            $table->string('email_customer');
            $table->string('password_customer');
            $table->string('no_telp_customer',13);
            $table->string('ktp_customer');
            $table->string('status_customer',15)->default('Unverified');
            $table->integer('rating_ajr')->nullable();
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
        Schema::dropIfExists('customers');
    }
};
