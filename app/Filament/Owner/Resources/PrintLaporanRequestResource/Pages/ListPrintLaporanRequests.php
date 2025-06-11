<?php

namespace App\Filament\Owner\Resources\PrintLaporanRequestResource\Pages;

use App\Filament\Owner\Resources\PrintLaporanRequestResource;
use Filament\Resources\Pages\ListRecords;
use Filament\Actions\Action;
use App\Models\RequestDonasi;

class ListPrintLaporanRequests extends ListRecords
{
    protected static string $resource = PrintLaporanRequestResource::class;

    protected function getHeaderActions(): array
    {
        return [
            Action::make('preview_diproses')
                ->label('Unduh Laporan Request Donasi')
                ->button()
                ->color('info')
                ->action(function (): void {
                    $this->redirect(route('owner.laporan.request.preview', ['status' => 'Diproses']));
                }),
        ];
    }

    protected function getTableActions(): array
    {
        return [];
    }
}
