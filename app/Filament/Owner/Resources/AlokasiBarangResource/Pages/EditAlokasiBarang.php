<?php

namespace App\Filament\Owner\Resources\AlokasiBarangResource\Pages;

use App\Filament\Owner\Resources\AlokasiBarangResource;
use Filament\Actions;
use Filament\Resources\Pages\EditRecord;

class EditAlokasiBarang extends EditRecord
{
    protected static string $resource = AlokasiBarangResource::class;

    protected function getHeaderActions(): array
    {
        return [
            Actions\DeleteAction::make(),
        ];
    }
}
