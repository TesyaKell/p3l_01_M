<?php

namespace App\Filament\Owner\Resources\PrintLaporanRequestResource\Pages;

use App\Filament\Owner\Resources\PrintLaporanRequestResource;
use Filament\Actions;
use Filament\Resources\Pages\EditRecord;

class EditPrintLaporanRequest extends EditRecord
{
    protected static string $resource = PrintLaporanRequestResource::class;

    protected function getHeaderActions(): array
    {
        return [
            Actions\DeleteAction::make(),
        ];
    }
}
