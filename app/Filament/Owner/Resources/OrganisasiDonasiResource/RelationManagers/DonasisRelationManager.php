<?php

namespace App\Filament\Owner\Resources\OrganisasiDonasiResource\RelationManagers;

use Filament\Forms;
use Filament\Forms\Form;
use Filament\Resources\RelationManagers\RelationManager;
use Filament\Tables;
use Filament\Tables\Table;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\SoftDeletingScope;

class DonasisRelationManager extends RelationManager
{
    protected static string $relationship = 'donasis';
    protected static ?string $recordTitleAttribute = 'id_donasi';
    protected static ?string $title = 'Riwayat Donasi';

    public function form(Form $form): Form
    {
        return $form
            ->schema([
                // Form fields not needed as we're only displaying history
            ]);
    }

    public function table(Table $table): Table
    {
        return $table
            ->recordTitleAttribute('id_donasi')
            ->columns([
                Tables\Columns\TextColumn::make('id_donasi')
                    ->searchable()
                    ->sortable(),
                Tables\Columns\TextColumn::make('request.desk_request')
                    ->searchable(),
                Tables\Columns\TextColumn::make('barang.nama_barang')
                    ->searchable(),
                Tables\Columns\TextColumn::make('penitip.nama_penitip')
                    ->searchable(),
                Tables\Columns\TextColumn::make('tanggal_donasi')
                    ->date()
                    ->sortable(),
                Tables\Columns\TextColumn::make('nama_penerima')
                    ->searchable(),
            ])
            ->filters([
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
            ->headerActions([
                // No create action as we're only displaying history
            ])
            ->actions([
                Tables\Actions\ViewAction::make(),
            ])
            ->bulkActions([
                // No bulk actions needed
            ]);
    }

    public function isReadOnly(): bool
    {
        return true;
    }
}
