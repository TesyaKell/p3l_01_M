<?php

namespace App\Filament\QC\Resources\RequestAmbilResource\Pages;

use App\Filament\QC\Resources\RequestAmbilResource;
use Filament\Actions;
use Filament\Resources\Pages\EditRecord;

class EditRequestAmbil extends EditRecord
{
    protected static string $resource = RequestAmbilResource::class;

    protected function getHeaderActions(): array
    {
        return [
            Actions\DeleteAction::make(),
        ];
    }
}
