<?php

namespace App\Filament\Owner\Resources\PrintLaporanTransaksiPenitipResource\Pages;

use App\Filament\Owner\Resources\PrintLaporanTransaksiPenitipResource;
use Filament\Actions;
use Filament\Resources\Pages\EditRecord;

class EditPrintLaporanTransaksiPenitip extends EditRecord
{
    protected static string $resource = PrintLaporanTransaksiPenitipResource::class;

    protected function getHeaderActions(): array
    {
        return [
            Actions\DeleteAction::make(),
        ];
    }
}
