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
        Schema::create('poin_histori', function (Blueprint $table) {
            $table->id('id_histori');
            $table->unsignedBigInteger('id_pelanggan');
            $table->enum('jenis', ['tambah', 'kurang']);
            $table->integer('jumlah_poin');
            $table->text('keterangan')->nullable();
            $table->datetime('tanggal');
            $table->timestamps();

            $table->foreign('id_pelanggan')->references('id_pelanggan')->on('pelanggan')->onDelete('cascade');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('poin_histori');
    }
};
