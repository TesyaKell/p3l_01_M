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
        Schema::create('pegawais', function (Blueprint $table) {
            $table->uuid('id_pegawai')->primary();
            $table->foreignId('kode_jabatan')->constrained('jabatans')->onDelete('cascade')->onUpdate('cascade');
            $table->string('nama_pegawai');
            $table->string('email');
            $table->string('password');
            // $table->string('no_telp');
            $table->date('tanggal_lahir');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('pegawais');
    }
};
