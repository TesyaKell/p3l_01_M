<?php

namespace App\Filament\Owner\Resources;

use App\Models\Barang;
use App\Models\Donasi;
use Filament\Forms;
use Filament\Forms\Form;
use Filament\Resources\Resource;
use Filament\Tables;
use Filament\Tables\Table;
use Illuminate\Database\Eloquent\Builder;
use App\Filament\Owner\Resources\DonasiResource\Pages\ListDonasis;

class DonasiResource extends Resource
{
    protected static ?string $model = Donasi::class;
    protected static ?string $navigationIcon = 'heroicon-o-heart';
    protected static ?string $navigationGroup = 'Donasi Management';
    protected static ?string $navigationLabel = 'Donasi';
    protected static ?int $navigationSort = 2;

    public static function form(Form $form): Form
    {
        return $form->schema([]);
    }

    public static function table(Table $table): Table
    {
        return $table
            ->columns([
                Tables\Columns\TextColumn::make('id_donasi')->searchable()->sortable(),
                Tables\Columns\TextColumn::make('request.desk_request')->searchable(),
                Tables\Columns\TextColumn::make('barang.nama_barang')->searchable(),
                Tables\Columns\TextColumn::make('penitip.nama_penitip')->searchable(),
                Tables\Columns\TextColumn::make('tanggal_donasi')->date()->sortable(),
                Tables\Columns\TextColumn::make('nama_penerima')->searchable(),
                Tables\Columns\TextColumn::make('request.organisasi.nama_organisasi')
                    ->label('Organisasi')
                    ->searchable(),
            ])
            ->filters([
                Tables\Filters\SelectFilter::make('organisasi')
                    ->relationship('request.organisasi', 'nama_organisasi'),
                Tables\Filters\Filter::make('tanggal_donasi')
                    ->form([
                        Forms\Components\DatePicker::make('dari_tanggal'),
                        Forms\Components\DatePicker::make('sampai_tanggal'),
                    ])
                    ->query(function (Builder $query, array $data): Builder {
                        return $query
                            ->when(
                                $data['dari_tanggal'],
                                fn (Builder $query, $date): Builder => $query->whereDate('tanggal_donasi', '>=', $date),
                            )
                            ->when(
                                $data['sampai_tanggal'],
                                fn (Builder $query, $date): Builder => $query->whereDate('tanggal_donasi', '<=', $date),
                            );
                    }),
            ])
            ->actions([]) // semua aksi dinonaktifkan
            ->bulkActions([]); // bulk delete juga dimatikan
    }

    public static function getRelations(): array
    {
        return [];
    }

    public static function getPages(): array
    {
        return [
            'index' => ListDonasis::route('/'),
        ];
    }

    public static function getWidgets(): array
    {
        return [];
    }
}
