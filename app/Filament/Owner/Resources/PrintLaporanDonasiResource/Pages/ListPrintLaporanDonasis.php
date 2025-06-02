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
                ->label('Preview & Unduh PDF')
                ->icon('heroicon-o-arrow-down-tray')
                ->action(function (): void {
                    $this->redirect(route('laporan.donasi.preview'));
                })
                ->color('primary'),
        ];
    }
}
