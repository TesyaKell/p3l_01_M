<?php

namespace App\Filament\Owner\Resources\PrintLaporanOwnerResource\Pages;

use App\Filament\Owner\Resources\PrintLaporanOwnerResource;
use Filament\Actions;
use Filament\Resources\Pages\EditRecord;

class EditPrintLaporanOwner extends EditRecord
{
    protected static string $resource = PrintLaporanOwnerResource::class;

    protected function getHeaderActions(): array
    {
        return [
            Actions\DeleteAction::make(),
        ];
    }
}
