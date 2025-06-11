<?php

namespace App\Filament\Owner\Resources;

use App\Models\RequestDonasi;
use Filament\Forms;
use Filament\Resources\Resource;
use Filament\Tables;
use Filament\Tables\Actions\Action;
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
            ->columns([
                TextColumn::make('organisasi.nama_organisasi')->label('Nama Organisasi')->searchable(),
                TextColumn::make('desk_request')->label('Deskripsi Request')->searchable(),
                TextColumn::make('status')->label('Status')->searchable(),
            ])
            ->actions([
                Action::make('unduh_pdf_diproses')
                    ->label('Unduh PDF (Diproses)')
                    ->icon('heroicon-o-arrow-down-tray')
                    ->url(fn() => route('owner.laporan.request.pdf', ['status' => 'Diproses']))
                    ->openUrlInNewTab(),
                Action::make('unduh_pdf_diterima')
                    ->label('Unduh PDF (Diterima)')
                    ->icon('heroicon-o-arrow-down-tray')
                    ->url(fn() => route('owner.laporan.request.pdf', ['status' => 'Diterima']))
                    ->openUrlInNewTab(),
            ]);
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
