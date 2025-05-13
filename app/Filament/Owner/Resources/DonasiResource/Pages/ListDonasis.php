<?php

namespace App\Filament\Owner\Resources\DonasiResource\Pages;

use App\Filament\Owner\Resources\DonasiResource;
use Filament\Actions;
use Filament\Resources\Pages\ListRecords;

class ListDonasis extends ListRecords
{
    protected static string $resource = DonasiResource::class;

    protected function getHeaderActions(): array
    {
        return [
            Actions\CreateAction::make(),
        ];
    }
}
