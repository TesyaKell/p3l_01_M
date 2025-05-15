<?php

namespace App\Filament\Owner\Resources\BarangDonasiResource\Pages;

use App\Filament\Owner\Resources\BarangDonasiResource;
use Filament\Actions;
use Filament\Resources\Pages\ListRecords;

class ListBarangDonasis extends ListRecords
{
    protected static string $resource = BarangDonasiResource::class;

    protected function getHeaderActions(): array
    {
        return [
            Actions\CreateAction::make(),
        ];
    }
}
