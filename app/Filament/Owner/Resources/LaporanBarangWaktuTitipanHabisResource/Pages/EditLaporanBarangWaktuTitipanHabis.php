<?php

namespace App\Filament\Owner\Resources\LaporanBarangWaktuTitipanHabisResource\Pages;

use App\Filament\Owner\Resources\LaporanBarangWaktuTitipanHabisResource;
use Filament\Actions;
use Filament\Resources\Pages\EditRecord;

class EditLaporanBarangWaktuTitipanHabis extends EditRecord
{
    protected static string $resource = LaporanBarangWaktuTitipanHabisResource::class;

    protected function getHeaderActions(): array
    {
        return [
            Actions\DeleteAction::make(),
        ];
    }
}
