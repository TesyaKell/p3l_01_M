<?php

namespace App\Console\Commands;

use Illuminate\Console\Command;
use App\Http\Controllers\BarangController;

class StatusDonasiNotify extends Command
{
    protected $signature = 'statusDonasi:notify';
    protected $description = 'Update status barang menjadi Donasi jika sudah lewat 7 hari';

    public function handle()
    {
        app(BarangController::class)->statusDonasi();

        \Log::info('✅ statusDonasi:notify berhasil dijalankan pada ' . now());
    }
}
