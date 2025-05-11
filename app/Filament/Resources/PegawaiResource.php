<?php

namespace App\Filament\Resources;

use App\Models\Pegawai;
use Filament\Forms;
use Filament\Resources\Resource;
use Filament\Tables\Actions\Action;
use Filament\Resources\Form;
use Filament\Resources\Table;
use Filament\Widgets\StatsOverview;
use Illuminate\Database\Eloquent\Builder;
use Filament\Resources\ResourceInterface;
use App\Filament\Resources\PegawaiResource\Pages\CreatePegawai;
use App\Filament\Resources\PegawaiResource\Pages\ListPegawais;
use App\Filament\Resources\PegawaiResource\Pages\EditPegawai;

class PegawaiResource extends Resource
{
    protected static ?string $model = Pegawai::class;
    protected static ?string $navigationIcon = 'heroicon-o-user';

    public static function form(Forms\Form $form): Forms\Form
    {
        return $form
            ->schema([
                Forms\Components\Select::make('kode_jabatan')
                    ->required()
                    ->label('Kode Jabatan')
                    ->options(\App\Models\Jabatan::pluck('nama_jabatan', 'kode_jabatan')->toArray()),
                Forms\Components\TextInput::make('nama_pegawai')
                    ->required()
                    ->label('Nama Pegawai'),
                Forms\Components\TextInput::make('no_telp')
                    ->label('No. Telepon'),
                Forms\Components\TextInput::make('email')
                    ->required()
                    ->email()
                    ->label('Email Pegawai'),
                Forms\Components\TextInput::make('password')
                    ->password()
                    ->label('Password')
                    ->dehydrateStateUsing(fn ($state) => bcrypt($state)),
                Forms\Components\DatePicker::make('tanggal_lahir')
                    ->label('Tanggal Lahir'),
            ]);
    }

    public static function table(\Filament\Tables\Table $table): \Filament\Tables\Table
    {
        return $table
            ->columns([
                \Filament\Tables\Columns\TextColumn::make('id_pegawai')
                    ->label('ID Pegawai')
                    ->sortable()
                    ->searchable(),
                \Filament\Tables\Columns\TextColumn::make('kode_jabatan')
                    ->label('Kode Jabatan')
                    ->sortable()
                    ->searchable(),
                \Filament\Tables\Columns\TextColumn::make('nama_pegawai')
                    ->label('Nama Pegawai')
                    ->sortable()
                    ->searchable(),
                \Filament\Tables\Columns\TextColumn::make('no_telp')
                    ->label('No. Telepon')
                    ->searchable(),
                \Filament\Tables\Columns\TextColumn::make('email')
                    ->label('Email')
                    ->searchable(),
                \Filament\Tables\Columns\TextColumn::make('password')
                    ->label('Password')
                    ->hidden(),
                \Filament\Tables\Columns\TextColumn::make('tanggal_lahir')
                    ->label('Tanggal Lahir')
                    ->date(),
            ])
            ->filters([])
            ->actions([
                Action::make('forget_password')
                    ->label('Forget Password')
                    ->action(function ($record) {
                        $tanggalLahir = \Carbon\Carbon::parse($record->tanggal_lahir);
                        $record->update([
                            'password' => bcrypt($tanggalLahir->format('Ymd')),
                        ]);
                    })
                    ->requiresConfirmation()
                    ->color('danger'),
            ]);
    }

    public static function navigationLabel(?string $label = null): void
    {
        if ($label === null) {
            $label = 'Tambah Pegawai';
        }
    }

    public static function getPages(): array
    {
        return [
            'index' => ListPegawais::route('/'),
            'create' => CreatePegawai::route('/create'),
            'edit' => EditPegawai::route('/{record}/edit'),
        ];
    }
}
