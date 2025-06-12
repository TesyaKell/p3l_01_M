<?php

namespace App\Filament\Owner\Resources\LaporanPenjualanKategoriResource\Pages;

use App\Filament\Owner\Resources\LaporanPenjualanKategoriResource;
use Filament\Actions;
use Filament\Resources\Pages\EditRecord;

class EditLaporanPenjualanKategori extends EditRecord
{
    protected static string $resource = LaporanPenjualanKategoriResource::class;

    protected function getHeaderActions(): array
    {
        return [
            Actions\DeleteAction::make(),
        ];
    }
}
