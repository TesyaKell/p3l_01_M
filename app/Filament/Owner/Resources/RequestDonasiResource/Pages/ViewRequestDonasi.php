<?php

namespace App\Filament\Owner\Resources\RequestDonasiResource\Pages;

use App\Filament\Owner\Resources\RequestDonasiResource;
use Filament\Resources\Pages\ViewRecord;
use Filament\Forms;

class ViewRequestDonasi extends ViewRecord
{
    protected static string $resource = RequestDonasiResource::class;

    protected function getFormSchema(): array
    {
        return [
            Forms\Components\Placeholder::make('id_request')
                ->label('ID Request')
                ->content(fn($record) => $record->id_request),

            Forms\Components\Placeholder::make('organisasi')
                ->label('Organisasi')
                ->content(fn($record) => $record->organisasi->nama_organisasi ?? '-'),

            Forms\Components\Placeholder::make('desk_request')
                ->label('Deskripsi Permintaan')
                ->content(fn($record) => $record->desk_request),

            Forms\Components\Placeholder::make('created_at')
                ->label('Tanggal Request')
                ->content(fn($record) => \Carbon\Carbon::parse($record->created_at)->translatedFormat('d F Y H:i')),

            Forms\Components\Placeholder::make('status')
                ->label('Status')
                ->content(fn($record) => $record->status ?? 'Tidak ada status'),
        ];
    }
}
