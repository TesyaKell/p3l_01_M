<?php

namespace App\Filament\Owner\Resources\LaporanStokGudangResource\Widgets;

use App\Models\Barang;
use App\Models\Penitip;
use App\Models\Pegawai;
use Filament\Widgets\StatsOverviewWidget as BaseWidget;
use Filament\Widgets\StatsOverviewWidget\Stat;
use Illuminate\Support\Carbon;
use Illuminate\Support\Facades\DB;

class CurrentStockOverviewWidget extends BaseWidget
{
    protected function getStats(): array
    {
        $today = now();

        // Only current stock (available items)
        $currentStock = Barang::where('status', 'Tersedia');

        $totalStok = $currentStock->count();
        $nilaiTotalStok = $currentStock->sum('harga');

        // Stock age analysis
        $barangBaru = $currentStock->where('tanggal_masuk', '>=', $today->copy()->subDays(30))->count();
        $barangLama = $currentStock->where('tanggal_masuk', '<', $today->copy()->subDays(60))->count();


        // Items with hunters
        $denganHunter = Barang::where('status', 'Tersedia')
    ->whereNotNull('id_hunter_pegawai')
    ->count();

        // Items approaching expiry
        $mendekatiExpiry = $currentStock->whereNotNull('tanggal_batas')
            ->where('tanggal_batas', '<=', $today->copy()->addDays(7))
            ->count();

        // Items past expiry
        $lewatBatas = $currentStock->whereNotNull('tanggal_batas')
            ->where('tanggal_batas', '<', $today)
            ->count();

        $persentaseBaru = $totalStok > 0 ? round(($barangBaru / $totalStok) * 100, 1) : 0;

        $totalBarang = Barang::count();
        $totalTersedia = Barang::where('status', 'Tersedia')->count();
        $totalTerjual = Barang::where('status', 'Terjual')->count();
        $totalTerdonasi = Barang::where('status', 'Terdonasi')->count();

        $nilaiTotalStok = Barang::where('status', 'Tersedia')->sum('harga');
        $nilaiTotalTerjual = Barang::where('status', 'Terjual')->sum('harga');

        $totalPenitip = Penitip::count();
        $totalHunter = Pegawai::whereNotNull('id_pegawai')
            ->whereIn('id_pegawai', function ($query) {
                $query->select('id_hunter_pegawai')
                      ->from('barang')
                      ->whereNotNull('id_hunter_pegawai');
            })->count();

        $persentaseTersedia = $totalBarang > 0 ? round(($totalTersedia / $totalBarang) * 100, 1) : 0;
        $persentaseTerjual = $totalBarang > 0 ? round(($totalTerjual / $totalBarang) * 100, 1) : 0;
        return [

            Stat::make('Nilai Total Stok', 'Rp ' . number_format($nilaiTotalStok, 0, ',', '.'))
                ->description('Nilai keseluruhan stok')
                ->descriptionIcon('heroicon-m-currency-dollar')
                ->color('success'),



            Stat::make('Dengan Hunter', $denganHunter)
                ->description('Barang yang memiliki hunter')
                ->descriptionIcon('heroicon-m-user-group')
                ->color('gray'),

            Stat::make('Total Barang', $totalBarang)
                ->description('Total item di gudang')
                ->descriptionIcon('heroicon-m-archive-box')
                ->color('primary'),

            Stat::make('Barang Tersedia', $totalTersedia)
                ->description($persentaseTersedia . '% dari total barang')
                ->descriptionIcon('heroicon-m-check-circle')
                ->color('success'),
            Stat::make('Total Penitip', $totalPenitip)
                ->description('Penitip aktif')
                ->descriptionIcon('heroicon-m-users')
                ->color('gray'),
        ];
    }
}
