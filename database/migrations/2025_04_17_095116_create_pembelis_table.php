<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration {
    /**
     * Run the migrations.
     */
    public function up(): void
    {
        Schema::create('pembeli', function (Blueprint $table) {
            $table->uuid('id_pembeli')->primary();
            $table->string('nama_pembeli');
            $table->string('no_telp')->unique();
            $table->string('email')->unique();
            $table->string('password');
            $table->date('tanggal_lahir');
            $table->integer('poin')->default(0);
            $table->double('saldo')->default(0);
            $table->timestamp('email_verified_at')->nullable();
            $table->string('verify_key')->nullable();
            $table->rememberToken();
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('pembeli');
    }
};
