<?php

namespace App\Filament\Owner\Resources\HistoryDonasiResource\Pages;

use App\Filament\Owner\Resources\HistoryDonasiResource;
use Filament\Actions;
use Filament\Resources\Pages\EditRecord;

class EditHistoryDonasi extends EditRecord
{
    protected static string $resource = HistoryDonasiResource::class;

    protected function getHeaderActions(): array
    {
        return [
            Actions\DeleteAction::make(),
        ];
    }
}
