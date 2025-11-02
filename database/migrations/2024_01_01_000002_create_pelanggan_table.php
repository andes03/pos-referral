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
        Schema::create('pelanggan', function (Blueprint $table) {
            $table->id('id_pelanggan');
            $table->string('nama', 100);
            $table->string('email', 100)->unique();
            $table->string('password');
            $table->string('no_telp', 20)->nullable();
            $table->text('alamat')->nullable();
            $table->integer('poin')->default(0);
            $table->string('kode_referal', 20)->unique();
            $table->string('kode_referal_digunakan', 20)->nullable();
            $table->string('image')->nullable();
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('pelanggan');
    }
};
