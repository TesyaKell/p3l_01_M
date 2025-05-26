<?php

namespace App\Filament\Owner\Resources;

use App\Filament\Owner\Resources\PrintLaporanOwnerResource\Pages;
use App\Models\PrintLaporanOwner;
use Filament\Forms;
use Filament\Forms\Form;
use Filament\Resources\Resource;
use Filament\Tables;
use Filament\Tables\Table;
use Filament\Tables\Actions\Action;

class PrintLaporanOwnerResource extends Resource
{
    protected static ?string $model = PrintLaporanOwner::class;

    protected static ?string $navigationIcon = 'heroicon-o-rectangle-stack';

    public static function form(Form $form): Form
    {
        return $form->schema([
            // Form schema jika diperlukan
        ]);
    }

    public static function table(Table $table): Table
    {
        return $table
            ->columns([
                Tables\Columns\TextColumn::make('id')->label('ID')->sortable(),
                Tables\Columns\TextColumn::make('judul_laporan')->label('Judul Laporan'),
                Tables\Columns\TextColumn::make('created_at')->label('Tanggal Dibuat')->dateTime('d M Y'),
            ])
            ->actions([
                Tables\Actions\ViewAction::make(),
                Tables\Actions\EditAction::make(),
                Action::make('download_pdf')
                    ->label('Unduh PDF')
                    ->icon('heroicon-o-download')
                    ->url(fn($record) => route('owner.laporan.download', ['id' => $record->id]))
                    ->openUrlInNewTab(),
            ])
            ->bulkActions([
                Tables\Actions\DeleteBulkAction::make(),
            ]);
    }

    public static function getRelations(): array
    {
        return [];
    }

    public static function getPages(): array
    {
        return [
            'index' => Pages\ListPrintLaporanOwners::route('/'),
            'create' => Pages\CreatePrintLaporanOwner::route('/create'),
            'edit' => Pages\EditPrintLaporanOwner::route('/{record}/edit'),
        ];
    }
}
