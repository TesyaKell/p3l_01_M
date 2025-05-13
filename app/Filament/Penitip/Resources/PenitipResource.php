<?php

namespace App\Filament\Penitip\Resources;

use App\Filament\Penitip\Resources\PenitipResource\Pages;
use App\Filament\Penitip\Resources\PenitipResource\RelationManagers;
use App\Models\Penitip;
use Filament\Forms;
use Filament\Forms\Form;
use Filament\Resources\Resource;
use Filament\Tables;
use Filament\Tables\Table;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\SoftDeletingScope;

class PenitipResource extends Resource
{
    protected static ?string $model = Penitip::class;

    protected static ?string $navigationIcon = 'heroicon-o-rectangle-stack';

    public static function form(Form $form): Form
    {
        return $form
            ->schema([
                //
            ]);
    }

    public static function table(Table $table): Table
    {
        return $table
            ->columns([
                Tables\Columns\TextColumn::make('id_penitip')
                    ->label('ID Penitip')
                    ->sortable()
                    ->searchable(),
                Tables\Columns\TextColumn::make('nama_penitip')
                    ->label('Nama Penitip')
                    ->sortable()
                    ->searchable(),
            ])
            ->filters([
                //
            ])
            ->actions([
                Tables\Actions\EditAction::make(),
            ])
            ->bulkActions([
                Tables\Actions\BulkActionGroup::make([
                    Tables\Actions\DeleteBulkAction::make(),
                ]),
            ]);
    }

    public static function getRelations(): array
    {
        return [
            //
        ];
    }

    public static function getPages(): array
    {
        return [
            'index' => Pages\ListPenitips::route('/'),
            'create' => Pages\CreatePenitip::route('/create'),
            'edit' => Pages\EditPenitip::route('/{record}/edit'),
        ];
    }
}
