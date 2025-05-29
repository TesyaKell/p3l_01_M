<?php

namespace App\Filament\Owner\Resources\PrintLaporanDonasiResource\Pages;

use App\Filament\Owner\Resources\PrintLaporanDonasiResource;
use Filament\Resources\Pages\ListRecords;
use Filament\Actions;

class ListPrintLaporanDonasis extends ListRecords
{
    protected static string $resource = PrintLaporanDonasiResource::class;

    protected function getHeaderActions(): array
    {
        return [
            Actions\Action::make('unduh_pdf')
                ->label('Unduh PDF')
                ->icon('heroicon-o-arrow-down-tray')
                ->url(route('owner.laporan.donasi.pdf'))
                ->openUrlInNewTab()
                ->color('primary'),
        ];
    }
}
