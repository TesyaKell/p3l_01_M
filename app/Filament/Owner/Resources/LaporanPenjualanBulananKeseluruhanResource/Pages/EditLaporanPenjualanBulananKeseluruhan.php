<?php

namespace App\Filament\Owner\Resources\LaporanPenjualanBulananKeseluruhanResource\Pages;

use App\Filament\Owner\Resources\LaporanPenjualanBulananKeseluruhanResource;
use Filament\Actions;
use Filament\Resources\Pages\EditRecord;

class EditLaporanPenjualanBulananKeseluruhan extends EditRecord
{
    protected static string $resource = LaporanPenjualanBulananKeseluruhanResource::class;

    protected function getHeaderActions(): array
    {
        return [
            Actions\DeleteAction::make(),
        ];
    }
}
