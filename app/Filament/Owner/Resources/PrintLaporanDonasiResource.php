<?php

namespace App\Filament\Owner\Resources;

use App\Models\Donasi;
use Filament\Forms;
use Filament\Resources\Resource;
use Filament\Tables;
use Filament\Tables\Actions\Action;
use Filament\Tables\Table;
use Filament\Tables\Columns\TextColumn;
use App\Filament\Owner\Resources\PrintLaporanDonasiResource\Pages;

class PrintLaporanDonasiResource extends Resource
{
    protected static ?string $model = Donasi::class;

    protected static ?string $navigationIcon = 'heroicon-o-document-text';
    protected static ?string $navigationLabel = 'Laporan Donasi';
    //protected static ?string $navigationGroup = 'Donasi Management';
    protected static ?string $navigationGroup = 'Laporan';
    protected static ?int $navigationSort = 5;

    public static function form(Forms\Form $form): Forms\Form
    {
        return $form->schema([]);
    }

    public static function table(Table $table): Table
    {
        return $table
            ->columns([
                TextColumn::make('nama_penitip')->label('Nama Donatur')->searchable(),
                TextColumn::make('barang.nama_barang')->label('Nama Barang')->searchable(),
                TextColumn::make('id_penitip')->label('Id Penitip')->searchable(),
                TextColumn::make('requestDonasi.organisasi.nama_organisasi')->label('Nama Organisasi')->searchable(),
                TextColumn::make('tanggal_donasi')->label('Tanggal Donasi')->date('d M Y')->sortable(),
                TextColumn::make('nama_penerima')->label('Nama Penerima')->searchable(),
                TextColumn::make('requestDonasi.organisasi.alamat')->label('Alamat')->searchable()
                    ->getStateUsing(function ($record) {
                        return $record->requestDonasi?->organisasi?->alamat ?? 'Alamat tidak tersedia';
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
            'index' => Pages\ListPrintLaporanDonasis::route('/'),
        ];
    }
}
