<?php

namespace App\Filament\Owner\Resources\BarangDonasiResource\Pages;

use App\Filament\Owner\Resources\BarangDonasiResource;
use Filament\Actions;
use Filament\Resources\Pages\EditRecord;

class EditBarangDonasi extends EditRecord
{
    protected static string $resource = BarangDonasiResource::class;

    protected function getHeaderActions(): array
    {
        return [
            Actions\DeleteAction::make(),
        ];
    }
}
