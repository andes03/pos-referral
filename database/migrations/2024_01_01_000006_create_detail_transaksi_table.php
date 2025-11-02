<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('transaksi', function (Blueprint $table) {
            $table->id('id_transaksi');
            $table->unsignedBigInteger('id_pelanggan');
            $table->unsignedBigInteger('id_pegawai');
            $table->decimal('total', 10, 2);
            $table->enum('metode_pembayaran', ['cash', 'qris', 'transfer']);
            $table->datetime('tanggal_transaksi');
            $table->timestamps();

            $table->foreign('id_pelanggan')->references('id_pelanggan')->on('pelanggan')->onDelete('cascade');
            $table->foreign('id_pegawai')->references('id_pegawai')->on('pegawai')->onDelete('cascade');
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('transaksi');
    }
};