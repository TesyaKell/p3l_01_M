<?php

namespace App\Filament\Owner\Resources\RequestDonasiResource\Pages;

use App\Filament\Owner\Resources\RequestDonasiResource;
use Filament\Actions;
use Filament\Resources\Pages\ListRecords;

class ListRequestDonasis extends ListRecords
{
    protected static string $resource = RequestDonasiResource::class;

    protected function getHeaderActions(): array
    {
        return [];
    }
}
