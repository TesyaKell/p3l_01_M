<?php

namespace App\Filament\Owner\Resources\PrintLaporanOwnerResource\Pages;

use App\Filament\Owner\Resources\PrintLaporanOwnerResource;
use Filament\Actions;
use Filament\Resources\Pages\ListRecords;

class ListPrintLaporanOwners extends ListRecords
{
    protected static string $resource = PrintLaporanOwnerResource::class;

    protected function getHeaderActions(): array
    {
        return [
            Actions\CreateAction::make(),
        ];
    }
}
