<?php

namespace App\Filament\Resources;

use App\Models\Jabatan;
use Filament\Forms;
use Filament\Resources\Resource;
use Filament\Forms\Form;
use Filament\Tables\Table;
use App\Filament\Resources\JabatanResource\Pages\CreateJabatan;
use App\Filament\Resources\JabatanResource\Pages\ListJabatan;
use App\Filament\Resources\JabatanResource\Pages\EditJabatan;

class JabatanResource extends Resource
{
    protected static ?string $model = Jabatan::class;
    protected static ?string $navigationIcon = 'heroicon-o-briefcase';

    public static function form(Form $form): Form
    {
        return $form
            ->schema([
                Forms\Components\TextInput::make('nama_jabatan')
                    ->required()
                    ->label('Nama Jabatan'),
            ]);
    }

    public static function table(Table $table): Table
    {
        return $table
            ->columns([
                \Filament\Tables\Columns\TextColumn::make('kode_jabatan')
                    ->label('Kode Jabatan')
                    ->sortable()
                    ->searchable(),
                \Filament\Tables\Columns\TextColumn::make('nama_jabatan')
                    ->label('Nama Jabatan')
                    ->sortable()
                    ->searchable(),
            ]);

    }

    public static function getPages(): array
    {
        return [
            'index' => ListJabatan::route('/'),
            'create' => CreateJabatan::route('/create'),
            'edit' => EditJabatan::route('/{record}/edit'),
        ];
    }

}
