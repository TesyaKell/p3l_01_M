<?php

namespace App\Filament\Owner\Resources\LaporanStokGudangResource\Pages;

use App\Filament\Owner\Resources\LaporanStokGudangResource;
use Filament\Actions;
use Filament\Resources\Pages\EditRecord;

class EditLaporanStokGudang extends EditRecord
{
    protected static string $resource = LaporanStokGudangResource::class;

    protected function getHeaderActions(): array
    {
        return [
            Actions\DeleteAction::make(),
        ];
    }
}
