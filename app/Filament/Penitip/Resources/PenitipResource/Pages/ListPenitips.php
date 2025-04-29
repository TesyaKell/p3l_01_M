<?php

namespace App\Filament\Penitip\Resources\PenitipResource\Pages;

use App\Filament\Penitip\Resources\PenitipResource;
use Filament\Actions;
use Filament\Resources\Pages\ListRecords;

class ListPenitips extends ListRecords
{
    protected static string $resource = PenitipResource::class;

    protected function getHeaderActions(): array
    {
        return [
            Actions\CreateAction::make(),
        ];
    }
}
