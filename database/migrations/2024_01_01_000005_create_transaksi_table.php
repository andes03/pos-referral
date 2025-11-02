<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    /**
     * Run the migrations.
     */
    public function up(): void
    {
        Schema::create('transaksi', function (Blueprint $table) {
            $table->id('id_transaksi');
            $table->unsignedBigInteger('id_pelanggan');
            $table->unsignedBigInteger('id_pegawai');
            $table->decimal('total', 10, 2);
            $table->enum('metode_pembayaran', ['cash', 'qris', 'transfer']);
            $table->enum('status_pembayaran', ['pending', 'paid', 'failed'])->default('pending');
            $table->datetime('tanggal_transaksi');
            $table->timestamps();

            $table->foreign('id_pelanggan')->references('id_pelanggan')->on('pelanggan')->onDelete('cascade');
            $table->foreign('id_pegawai')->references('id_pegawai')->on('pegawai')->onDelete('cascade');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('transaksi');
    }
};
