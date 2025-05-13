<?php

namespace App\Filament\Owner\Resources\DonasiResource\Pages;

use App\Filament\Owner\Resources\DonasiResource;
use Filament\Actions;
use Filament\Resources\Pages\EditRecord;

class EditDonasi extends EditRecord
{
    protected static string $resource = DonasiResource::class;

    protected function getHeaderActions(): array
    {
        return [
            Actions\DeleteAction::make(),
        ];
    }
}
