<?php

namespace App\Filament\Owner\Resources\PrintLaporanDonasiResource\Pages;

use App\Filament\Owner\Resources\PrintLaporanDonasiResource;
use Filament\Resources\Pages\ListRecords;
use Filament\Actions\Action;
use Filament\Forms\Components\TextInput;
use App\Models\Donasi;

class ListPrintLaporanDonasis extends ListRecords
{
    protected static string $resource = PrintLaporanDonasiResource::class;

    protected function getHeaderActions(): array
    {
        return [
            Action::make('unduh_pdf_donasi')
                ->label('Unduh PDF Laporan Donasi')
                ->button()
                ->form([
                    TextInput::make('tahun')
                        ->label('Tahun')
                        ->numeric()
                        ->required()
                        ->default(date('Y')),
                ])
                ->action(function (array $data): void {
                    $tahun = (int) $data['tahun'];

                    // Redirect ke halaman preview dengan parameter
                    $this->redirect(route('owner.laporan.donasi.preview', [
                        'tahun' => $tahun
                    ]));
                }),
        ];
    }

    // Override actions pada table supaya tidak ada tombol di tiap baris
    protected function getTableActions(): array
    {
        return [];
    }
}
