<?php

namespace App\Filament\Owner\Resources\BarangDonasiResource\Pages;

use App\Filament\Owner\Resources\BarangDonasiResource;
use Filament\Actions;
use Filament\Resources\Pages\ViewRecord;

class ViewBarangDonasi extends ViewRecord
{
    protected static string $resource = BarangDonasiResource::class;

    protected function getHeaderActions(): array
    {
        return [
            // No edit action as we're only viewing
        ];
    }
}
