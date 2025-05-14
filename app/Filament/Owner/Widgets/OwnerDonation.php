<?php

namespace App\Filament\Owner\Widgets;

use Filament\Widgets\StatsOverviewWidget\Stat;
use Filament\Widgets\StatsOverviewWidget;
use App\Models\Donasi;
use App\Models\DonationRequest;
use App\Models\Organisasi;
use App\Models\Pegawai;
use App\Models\Penitip;

class OwnerDonation extends StatsOverviewWidget
{
    protected function getStats(): array
    {
        return [
            Stat::make('Total Donasi', Donasi::count())
            ->description('Total donasi yang berhasil dikumpulkan')
            ->descriptionIcon('heroicon-m-arrow-trending-up')
            ->color('success'),

            Stat::make('Permintaan Donasi', DonationRequest::where('status', 'Diproses')->count())
            ->description('Jumlah permintaan donasi yang sedang diproses')
            ->descriptionIcon('heroicon-m-arrow-trending-up')
            ->color('warning'),

            Stat::make('Organisasi Terdaftar', Organisasi::count())
                ->description('Organisasi yang bergabung dalam sistem')
                ->descriptionIcon('heroicon-m-building-office')
                ->color('info'),

            Stat::make('Jumlah Pegawai', Pegawai::count())
                ->description('Pegawai aktif yang terdaftar')
                ->descriptionIcon('heroicon-m-users')
                ->color('primary'),

            Stat::make('Jumlah Penitip', Penitip::count())
                ->description('Penitip yang telah terdaftar')
                ->descriptionIcon('heroicon-m-user-group')
                ->color('secondary'),
        ];
    }
}
