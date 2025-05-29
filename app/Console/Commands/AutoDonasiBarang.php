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
    protected $signature = 'app:auto-donasi-barang';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Command description';

    /**
     * Execute the console command.
     */
    public function handle()
    {
        app(\App\Http\Controllers\BarangController::class)->autoDonasikanBarang();
        $this->info('Barang otomatis didonasikan.');
    }

}
