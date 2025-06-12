<?php

namespace App\Console\Commands;

use Illuminate\Console\Command;
use App\Http\Controllers\BarangController;

class TopSeller extends Command
{
    protected $signature = 'topSeller:notify';
    protected $description = 'Menambahkan poin untuk top seller';


    public function handle()
    {
        app(BarangController::class)->topSeller();

        \Log::info('✅ topSeller:notify berhasil dijalankan pada ' . now());
    }
}
