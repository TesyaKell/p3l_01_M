<?php

namespace App\Filament\Resources;

use App\Filament\Resources\OrganisasiResource\Pages;
use App\Filament\Resources\OrganisasiResource\RelationManagers;
use App\Models\Organisasi;
use Filament\Forms;
use Filament\Forms\Form;
use Filament\Resources\Resource;
use Filament\Tables;
use Filament\Tables\Table;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\SoftDeletingScope;

class OrganisasiResource extends Resource
{
    protected static ?string $model = Organisasi::class;

    protected static ?string $navigationIcon = 'heroicon-o-rectangle-stack';

    public static function form(Form $form): Form
    {
        return $form
            ->schema([
                Forms\Components\TextInput::make('nama_organisasi')
                    ->required()
                    ->label('Nama Organisasi'),

                Forms\Components\Textarea::make('deskripsi')
                    ->required()
                    ->label('Deskripsi'),

                Forms\Components\TextInput::make('no_telp')
                    ->required()
                    ->label('Nomor Telepon'),

                Forms\Components\TextInput::make('email')
                    ->email()
                    ->required()
                    ->label('Email'),

                Forms\Components\TextInput::make('password')
                    ->password()
                    ->required()
                    ->label('Password')
                    ->dehydrated(fn ($state) => filled($state))
                    ->afterStateHydrated(fn ($component, $record) => $component->state(''))
                    ->afterStateUpdated(fn ($state, callable $set) => $set('password', Hash::make($state))),

                Forms\Components\Hidden::make('verify_key')
                    ->default(fn () => \Str::random(40)),

                Forms\Components\DateTimePicker::make('email_verified_at')
                    ->nullable()
                    ->label('Email Verified At'),

                Forms\Components\Hidden::make('remember_token')
                    ->default(fn () => \Str::random(60)),
            ]);
    }

    public static function table(Table $table): Table
    {
        return $table
            ->columns([
                Tables\Columns\TextColumn::make('id_organisasi')->label('ID')->sortable()->searchable(),
                Tables\Columns\TextColumn::make('nama_organisasi')->label('Nama')->sortable()->searchable(),

                Tables\Columns\TextColumn::make('no_telp')->label('Telepon'),
                Tables\Columns\TextColumn::make('email')->label('Email'),
                Tables\Columns\TextColumn::make('created_at')->label('Dibuat')->dateTime(),
            ])
            ->filters([])

            ->bulkActions([
                Tables\Actions\DeleteBulkAction::make(),
            ]);
    }

    public static function getPages(): array
    {
        return [
            'index' => Pages\ListOrganisasis::route('/'),
            'create' => Pages\CreateOrganisasi::route('/create'),
            'edit' => Pages\EditOrganisasi::route('/{record}/edit'),
        ];
    }
}
