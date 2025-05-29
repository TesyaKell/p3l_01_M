<?php

namespace App\Filament\Owner\Resources\PrintLaporanDonasiResource\Pages;

use App\Filament\Owner\Resources\PrintLaporanDonasiResource;
use Filament\Actions;
use Filament\Resources\Pages\EditRecord;

class EditPrintLaporanDonasi extends EditRecord
{
    protected static string $resource = PrintLaporanDonasiResource::class;

    protected function getHeaderActions(): array
    {
        return [
            Actions\DeleteAction::make(),
        ];
    }
}
