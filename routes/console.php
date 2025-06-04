<?php

use App\Models\Pembeli;
use App\Models\Penitip;
use App\Models\Transaksi;
use App\Notifications\MobileNotif;
use Carbon\Carbon;
use Illuminate\Foundation\Inspiring;
use Illuminate\Support\Facades\Artisan;
use Illuminate\Support\Facades\Schedule;

Artisan::command('inspire', function () {
    $this->comment(Inspiring::quote());
})->purpose('Display an inspiring quote');

Schedule::command('auto:donasi-barang')->everyFiveMinutes();

Schedule::call(function () {
    try {
        $currentMinute = Carbon::now()->startOfMinute(); // e.g., 2025-06-04 22:04:00
        $nextMinute = $currentMinute->copy()->addMinute(); // e.g., 2025-06-04 22:05:00

        $transaksi = Transaksi::where('status', 'Dikirim')
            ->where('tipe_delivery', 'kurir')
            ->whereNotNull('tanggal_ambil_kirim')
            ->where('tanggal_ambil_kirim', '>=', $currentMinute->format('Y-m-d H:i:s'))
            ->where('tanggal_ambil_kirim', '<', $nextMinute->format('Y-m-d H:i:s'))
            ->get();

        if ($transaksi->isEmpty()) {
            Log::info('No transactions found for notification.');
            return;
        }

        Log::info('Sending notifications for ' . $transaksi->count() . ' transactions.');

        foreach ($transaksi as $item) {
            $penitip = Penitip::find($item->detailTransaksi->first()->barang->id_penitip);
            $pembeli = Pembeli::find($item->id_pembeli);
            if ($penitip) {
                $penitip->notify(new MobileNotif(
                    'Barang Anda Sedang Dikirim!',
                    'Barang kamu sedang dalam proses pengiriman oleh kurir.'
                ));
            }

            if ($pembeli) {
                $pembeli->notify(new MobileNotif(
                    'Pengiriman Sedang Berlangsung',
                    'Barang Anda sedang dalam proses pengiriman.'
                ));
            }
        }

        Log::info('Notifications sent successfully.');
    } catch (\Exception $e) {
        Log::error('Error sending scheduled notifications: ' . $e->getMessage());
    }
})->everyMinute()
    // ->between('7:00', '16:00')
    ->name('send-notifications')
    ->withoutOverlapping()
    ->onFailure(function () {
        Log::error('Failed to send scheduled notifications.');
    });
