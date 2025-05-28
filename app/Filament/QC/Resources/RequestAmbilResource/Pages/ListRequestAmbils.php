<?php

namespace App\Filament\QC\Resources\RequestAmbilResource\Pages;

use App\Filament\QC\Resources\RequestAmbilResource;
use Filament\Actions;
use Filament\Resources\Pages\ListRecords;

class ListRequestAmbils extends ListRecords
{
    protected static string $resource = RequestAmbilResource::class;

    protected function getHeaderActions(): array
    {
        return [
            Actions\CreateAction::make(),
        ];
    }
}
