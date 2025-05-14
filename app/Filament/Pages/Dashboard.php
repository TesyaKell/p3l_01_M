<?php

namespace App\Filament\Pages;

use Filament\Pages\Page;
use App\Filament\Widgets\AdminDashboard;

class Dashboard extends Page
{
    protected static ?string $navigationIcon = 'heroicon-o-document-text';

    protected static string $view = 'filament.pages.dashboard';

    protected function getFooterWidgets(): array
    {
        return [
            AdminDashboard::class,
        ];
    }
}
