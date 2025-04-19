<?php

namespace App\Filament\Resources\OrganisaiResource\Pages;

use App\Filament\Resources\OrganisaiResource;
use Filament\Actions;
use Filament\Resources\Pages\ListRecords;

class ListOrganisais extends ListRecords
{
    protected static string $resource = OrganisaiResource::class;

    protected function getHeaderActions(): array
    {
        return [
            Actions\CreateAction::make(),
        ];
    }
}
