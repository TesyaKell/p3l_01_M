<?php

namespace App\Filament\QC\Resources\TransaksiKirimResource\Pages;

use App\Filament\QC\Resources\TransaksiKirimResource;
use Filament\Actions;
use Filament\Resources\Pages\EditRecord;

class EditTransaksiKirim extends EditRecord
{
    protected static string $resource = TransaksiKirimResource::class;

    protected function getHeaderActions(): array
    {
        return [
            Actions\DeleteAction::make(),
        ];
    }
}
