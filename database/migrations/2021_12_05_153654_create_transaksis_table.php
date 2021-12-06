<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateTransaksisTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('transaksis', function (Blueprint $table) {
            $table->id();
            $table->string('kode');
            $table->date('tgl');
            $table->foreignId('id_pelanggan')->nullable();
            $table->foreign('id_pelanggan')->references('id')->on('pelanggans');
            $table->foreignId('id_petugas');
            $table->foreign('id_petugas')->references('id')->on('users');
            $table->boolean('status');
            $table->integer('total')->nullable();
            $table->integer('tunai')->nullable();
            $table->integer('kembalian')->nullable();
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
        Schema::dropIfExists('transaksis');
    }
}
