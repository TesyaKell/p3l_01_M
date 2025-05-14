<?php

namespace App\Filament\Owner\Resources\AlokasiBarangResource\Pages;

use App\Filament\Owner\Resources\AlokasiBarangResource;
use Filament\Actions;
use Filament\Resources\Pages\ListRecords;

class ListAlokasiBarangs extends ListRecords
{
    protected static string $resource = AlokasiBarangResource::class;

    protected function getHeaderActions(): array
    {
        return [

        ];
    }
}
