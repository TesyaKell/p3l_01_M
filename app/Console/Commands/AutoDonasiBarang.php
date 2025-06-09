<?php

namespace App\Console\Commands;

use Illuminate\Console\Command;

class AutoDonasiBarang extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'auto:donasi-barang';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Otomatis mendonasikan barang jika tidak dibayar/diambil tepat waktu';

    /**
     * Execute the console command.
     */
    public function handle()
    {
        app(\App\Http\Controllers\BarangController::class)->autoDonasikanBarang();
        $this->info('Barang otomatis didonasikan.');
    }

}
