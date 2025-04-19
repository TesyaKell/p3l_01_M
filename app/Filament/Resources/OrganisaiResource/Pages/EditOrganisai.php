<?php

namespace App\Filament\Resources\OrganisaiResource\Pages;

use App\Filament\Resources\OrganisaiResource;
use Filament\Actions;
use Filament\Resources\Pages\EditRecord;

class EditOrganisai extends EditRecord
{
    protected static string $resource = OrganisaiResource::class;

    protected function getHeaderActions(): array
    {
        return [
            Actions\DeleteAction::make(),
        ];
    }
}
