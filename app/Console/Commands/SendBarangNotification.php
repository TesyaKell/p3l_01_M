<?php

namespace App\Console\Commands;

use Illuminate\Console\Command;
use App\Http\Controllers\BarangController;
use Illuminate\Http\Request;

class SendBarangNotification extends Command
{
    protected $signature = 'barang:notify';
    protected $description = 'Kirim notifikasi barang';

    public function handle()
    {
        app(BarangController::class)->notifikasi(new Request());

        $this->info('Notifikasi barang telah dikirim.');
    }
}
