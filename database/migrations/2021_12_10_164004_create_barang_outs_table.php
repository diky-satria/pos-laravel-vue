<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateBarangOutsTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('barang_outs', function (Blueprint $table) {
            $table->id();
            $table->date('tanggal');
            $table->foreignId('id_petugas');
            $table->foreign('id_petugas')->references('id')->on('users');
            $table->foreignId('id_barang');
            $table->foreign('id_barang')->references('id')->on('barangs');
            $table->integer('pengurangan');
            $table->text('keterangan');
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
        Schema::dropIfExists('barang_outs');
    }
}
