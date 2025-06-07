<?php

namespace App\Filament\Owner\Resources\LaporanKomisiBulananperProdukResource\Pages;

use App\Filament\Owner\Resources\LaporanKomisiBulananperProdukResource;
use Filament\Actions;
use Filament\Resources\Pages\EditRecord;

class EditLaporanKomisiBulananperProduk extends EditRecord
{
    protected static string $resource = LaporanKomisiBulananperProdukResource::class;

    protected function getHeaderActions(): array
    {
        return [
            Actions\DeleteAction::make(),
        ];
    }
}
