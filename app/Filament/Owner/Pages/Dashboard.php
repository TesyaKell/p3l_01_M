<?php

namespace App\Filament\Owner\Pages;

use App\Filament\Owner\Widgets\OwnerDonation;
use Filament\Pages\Dashboard as BaseDashboard;

class Dashboard extends BaseDashboard
{
    protected static ?string $navigationIcon = 'heroicon-o-home';

    protected static string $view = 'filament.owner.pages.dashboard';

    protected static ?string $title = 'Dashboard 0wner';

    protected function getFooterWidgets(): array
    {
        return [
            OwnerDonation::class,
        ];
    }
}
