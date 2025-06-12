<?php

namespace App\Filament\Owner\Resources;

use App\Models\RequestDonasi;
use Filament\Forms;
use Filament\Resources\Resource;
use Filament\Tables;
use Filament\Tables\Table;
use Filament\Tables\Columns\TextColumn;
use App\Filament\Owner\Resources\PrintLaporanRequestResource\Pages;

class PrintLaporanRequestResource extends Resource
{
    protected static ?string $model = RequestDonasi::class;

    protected static ?string $navigationIcon = 'heroicon-o-inbox';
    protected static ?string $navigationLabel = 'Laporan Request Donasi';
    protected static ?string $navigationGroup = 'Laporan';

    protected static ?int $navigationSort = 6;

    public static function form(Forms\Form $form): Forms\Form
    {
        return $form->schema([]);
    }

    public static function table(Table $table): Table
    {
        return $table
            ->query(RequestDonasi::query()->where('status', 'Diproses')->with('organisasi'))
            ->columns([
                //TextColumn::make('id_request')->label('ID Request')->searchable(),
                TextColumn::make('organisasi.id_organisasi')->label('ID Organisasi')->searchable(),
                TextColumn::make('organisasi.nama_organisasi')->label('Nama Organisasi')->searchable(),
                TextColumn::make('organisasi.alamat')->label('Alamat')->searchable(),
                TextColumn::make('desk_request')->label('Request')->searchable(),
                TextColumn::make('status')->label('Status')->badge()
                    ->color(fn(string $state): string => match ($state) {
                        'Diproses' => 'warning',
                        default => 'gray',
                    }),
            ])
            ->actions([]);
    }

    public static function getRelations(): array
    {
        return [];
    }

    public static function getPages(): array
    {
        return [
            'index' => Pages\ListPrintLaporanRequests::route('/'),
        ];
    }
}
