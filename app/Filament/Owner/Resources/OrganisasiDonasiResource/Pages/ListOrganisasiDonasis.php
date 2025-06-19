<?php

namespace App\Filament\Owner\Resources\OrganisasiDonasiResource\Pages;

use App\Filament\Owner\Resources\OrganisasiDonasiResource;
use Filament\Actions;
use Filament\Resources\Pages\ListRecords;

class ListOrganisasiDonasis extends ListRecords
{
    protected static string $resource = OrganisasiDonasiResource::class;

    // protected function getHeaderActions(): array
    // {
    //     return [
    //         Actions\CreateAction::make(),
    //     ];
    // }
}
