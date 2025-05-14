<?php

namespace App\Filament\Widgets;

use Filament\Widgets\StatsOverviewWidget\Stat;
use Filament\Widgets\StatsOverviewWidget;
use App\Models\Merchandise;
use App\Models\Jabatan;
use App\Models\Organisasi;
use App\Models\Pegawai;

class AdminDashboard extends StatsOverviewWidget
{
    protected function getStats(): array
    {
        return [
            Stat::make('Jumlah Jabatan', Jabatan::count())
                ->description('Total jabatan yang tersedia')
                ->descriptionIcon('heroicon-m-briefcase')
                ->color('info'),

            Stat::make('Jumlah Merchandise', Merchandise::count())
                ->description('Jumlah merchandise yang tersedia')
                ->descriptionIcon('heroicon-m-gift')
                ->color('success'),

            Stat::make('Jumlah Pegawai', Pegawai::count())
                ->description('Pegawai aktif yang terdaftar')
                ->descriptionIcon('heroicon-m-users')
                ->color('primary'),

            Stat::make('Jumlah Organisasi', Organisasi::count())
                ->description('Organisasi yang bergabung dalam sistem')
                ->descriptionIcon('heroicon-m-building-office')
                ->color('warning'),
        ];
    }

}
