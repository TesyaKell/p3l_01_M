<?php

namespace App\Filament\Owner\Resources;

use App\Filament\Owner\Resources\DonasiResource\Pages;
use App\Models\Donasi;
use Filament\Forms;
use Filament\Forms\Form;
use Filament\Resources\Resource;
use Filament\Tables;
use Filament\Tables\Table;

class DonasiResource extends Resource
{
    protected static ?string $model = Donasi::class;

    protected static ?string $navigationIcon = 'heroicon-o-rectangle-stack';

    public static function form(Form $form): Form
    {
        return $form
            ->schema([
                Forms\Components\Select::make('id_request')
                ->label('Request Donasi')
                ->options(\App\Models\RequestDonasi::all()->pluck('desk_request', 'id'))
                ->required(),


                Forms\Components\TextInput::make('nama_organisasi')
                    ->label('Nama Organisasi')
                    ->required(),

                Forms\Components\TextInput::make('nama_penerima')
                    ->label('Nama Penerima Donasi')
                    ->required(),

                Forms\Components\TextInput::make('nama_penitip')
                    ->label('Nama Penitip')
                    ->required(),


                Forms\Components\DatePicker::make('tanggal_donasi')
                    ->label('Tanggal Donasi')
                    ->required(),

                Tables\Columns\TextColumn::make('status')
                    ->label('Status')
                    ->badge()
                    ->color(fn (string $state): string => match ($state) {
                        'Diterima' => 'success',
                        'Ditolak' => 'danger',
                        default => 'gray',
                    }),
            ]);
    }

    public static function table(Table $table): Table
    {
        return $table
            ->columns([
                Tables\Columns\TextColumn::make('request.organisasi.nama_organisasi')->label('Nama Organisasi'),
                Tables\Columns\TextColumn::make('request.desk_request')->label('Deskripsi Request Donasi'),
                Tables\Columns\TextColumn::make('nama_penerima')->label('Nama Penerima'),
                Tables\Columns\TextColumn::make('nama_penitip')->label('Nama Penitip'),
                Tables\Columns\TextColumn::make('tanggal_donasi')->label('Tanggal Donasi'),
            ])
            ->filters([
                //
            ])
            ->actions([
                Tables\Actions\Action::make('diterima')
        ->label('Diterima')
        ->color('success')
        ->action(function ($record) {
            $record->request?->update(['status' => 'Diterima']);
        })
        ->icon('heroicon-o-check')
        ->requiresConfirmation(),

    Tables\Actions\Action::make('ditolak')
        ->label('Ditolak')
        ->color('danger')
        ->action(function ($record) {
            $record->request?->update(['status' => 'Ditolak']);
        })
        ->icon('heroicon-o-x-mark') // ikon default x
        ->requiresConfirmation(),
])

            ->bulkActions([
                            Tables\Actions\BulkActionGroup::make([
                                Tables\Actions\DeleteBulkAction::make(),
                            ]),
                        ]);
    }

    public static function getPages(): array
    {
        return [
            'index' => Pages\ListDonasis::route('/'),
            'create' => Pages\CreateDonasi::route('/create'),
            'edit' => Pages\EditDonasi::route('/{record}/edit'),
        ];
    }
}
