<?php

namespace App\Filament\Owner\Resources\DonasiResource\Widgets;

use App\Models\Donasi;
use App\Models\Organisasi;
use Filament\Widgets\StatsOverviewWidget as BaseWidget;
use Filament\Widgets\StatsOverviewWidget\Stat;
use Illuminate\Support\Facades\DB;

class DonasiOverview extends BaseWidget
{
    protected function getStats(): array
    {
        $totalDonasi = Donasi::count();

        $totalBarangDidonasikan = Donasi::distinct('kode_barang')->count('kode_barang');

        $organisasiStats = DB::table('donasi')
            ->join('request_donasi', 'donasi.id_request', '=', 'request_donasi.id_request')
            ->join('organisasi', 'request_donasi.id_organisasi', '=', 'organisasi.id_organisasi')
            ->select('organisasi.nama_organisasi', DB::raw('count(*) as total'))
            ->groupBy('organisasi.nama_organisasi')
            ->orderBy('total', 'desc')
            ->limit(1)
            ->first();

        $topOrganisasi = $organisasiStats ? $organisasiStats->nama_organisasi : '-';
        $topOrganisasiCount = $organisasiStats ? $organisasiStats->total : 0;

        return [
            Stat::make('Total Donasi', $totalDonasi)
                ->description('Total donasi yang telah dilakukan')
                ->descriptionIcon('heroicon-m-arrow-trending-up')
                ->color('success'),

            Stat::make('Total Barang Didonasikan', $totalBarangDidonasikan)
                ->description('Jumlah barang yang telah didonasikan')
                ->descriptionIcon('heroicon-m-gift')
                ->color('primary'),

            Stat::make('Organisasi Terbanyak', $topOrganisasi)
                ->description("Menerima {$topOrganisasiCount} donasi")
                ->descriptionIcon('heroicon-m-building-office')
                ->color('warning'),
        ];
    }
}
