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
        Schema::create('pegawai', function (Blueprint $table) {
            $table->id('id_pegawai');
            $table->string('nama', 100);
            $table->string('email', 100)->unique();
            $table->string('password');
            $table->enum('role', ['admin', 'user'])->default('user');
            $table->string('no_telp', 20)->nullable();
            $table->text('alamat')->nullable();
            $table->string('image')->nullable();
            $table->timestamps();
        });

        Schema::create('pelanggan', function (Blueprint $table) {
            $table->id('id_pelanggan');
            $table->string('nama', 100);
            $table->string('email', 100)->unique();
            $table->string('password');
            $table->string('no_telp', 20)->nullable();
            $table->text('alamat')->nullable();
            $table->string('kode_referal', 20)->unique();
            $table->string('image')->nullable();
            $table->timestamps();
        });

        Schema::create('kategori', function (Blueprint $table) {
            $table->id('id_kategori');
            $table->string('nama_kategori', 100);
            $table->text('deskripsi')->nullable();
            $table->timestamps();
        });

        Schema::create('produk', function (Blueprint $table) {
            $table->id('id_produk');
            $table->unsignedBigInteger('id_kategori');
            $table->string('nama', 100);
            $table->decimal('harga', 10, 2);
            $table->integer('stok')->default(0);
            $table->text('deskripsi')->nullable();
            $table->string('image')->nullable();
            $table->timestamps();

            $table->foreign('id_kategori')->references('id_kategori')->on('kategori')->onDelete('cascade');
        });

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

        Schema::create('detail_transaksi', function (Blueprint $table) {
            $table->id('id_detail_transaksi');
            $table->unsignedBigInteger('id_transaksi');
            $table->unsignedBigInteger('id_produk');
            $table->integer('jumlah');
            $table->decimal('subtotal', 10, 2);
            $table->timestamps();

            $table->foreign('id_transaksi')->references('id_transaksi')->on('transaksi')->onDelete('cascade');
            $table->foreign('id_produk')->references('id_produk')->on('produk')->onDelete('cascade');
        });

        Schema::create('sessions', function (Blueprint $table) {
            $table->string('id')->primary();
            $table->foreignId('user_id')->nullable()->index();
            $table->string('ip_address', 45)->nullable();
            $table->text('user_agent')->nullable();
            $table->longText('payload');
            $table->integer('last_activity')->index();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('sessions');
        Schema::dropIfExists('detail_transaksi');
        Schema::dropIfExists('transaksi');
        Schema::dropIfExists('produk');
        Schema::dropIfExists('kategori');
        Schema::dropIfExists('pelanggan');
        Schema::dropIfExists('pegawai');
    }
};
