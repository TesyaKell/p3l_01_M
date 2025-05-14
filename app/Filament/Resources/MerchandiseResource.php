<?php

namespace App\Filament\Resources;

use App\Filament\Resources\MerchandiseResource\Pages;
use App\Models\Merchandise;
use Filament\Forms;
use Filament\Forms\Form;
use Filament\Resources\Resource;
use Filament\Tables;
use Filament\Tables\Table;

class MerchandiseResource extends Resource
{
    protected static ?string $model = Merchandise::class;
    protected static ?string $navigationIcon = 'heroicon-o-rectangle-stack';

    public static function form(Form $form): Form
    {
        return $form
            ->schema([
                Forms\Components\TextInput::make('nama')
                    ->required()
                    ->label('Nama Merchandise'),

                Forms\Components\TextInput::make('stok')
                    ->numeric()
                    ->required()
                    ->label('Stok'),

                Forms\Components\TextInput::make('poin')
                    ->numeric()
                    ->required()
                    ->label('Poin'),

                Forms\Components\FileUpload::make('gambar')
                    ->image()
                    ->label('Gambar'),
            ]);
    }

    public static function table(Table $table): Table
    {
        return $table
            ->columns([
                Tables\Columns\TextColumn::make('id_merchandise')
                    ->label('ID')
                    ->sortable(),

                Tables\Columns\TextColumn::make('nama')
                    ->label('Nama')
                    ->searchable(),

                Tables\Columns\TextColumn::make('stok')
                    ->label('Stok'),

                Tables\Columns\TextColumn::make('poin')
                    ->label('Poin'),

                Tables\Columns\ImageColumn::make('gambar')
                    ->label('Gambar'),

                // Tables\Columns\TextColumn::make('created_at')
                //     ->label('Dibuat')
                //     ->dateTime(),

                // Tables\Columns\TextColumn::make('updated_at')
                //     ->label('Diperbarui')
                //     ->dateTime(),
            ])
            ->filters([])

            ->bulkActions([
                Tables\Actions\BulkActionGroup::make([
                    Tables\Actions\DeleteBulkAction::make(),
                ]),
            ]);
    }


    public static function getPages(): array
    {
        return [
            'index' => Pages\ListMerchandises::route('/'),
            'create' => Pages\CreateMerchandise::route('/create'),
            'edit' => Pages\EditMerchandise::route('/{record}/edit'),
        ];
    }
}
