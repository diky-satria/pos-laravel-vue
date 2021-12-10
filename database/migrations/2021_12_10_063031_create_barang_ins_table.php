<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateBarangInsTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('barang_ins', function (Blueprint $table) {
            $table->id();
            $table->date('tanggal');
            $table->foreignId('id_petugas');
            $table->foreign('id_petugas')->references('id')->on('users');
            $table->foreignId('id_barang');
            $table->foreign('id_barang')->references('id')->on('barangs');
            $table->integer('penambahan');
            $table->text('keterangan')->nullable();
            $table->boolean('status');
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
        Schema::dropIfExists('barang_ins');
    }
}
