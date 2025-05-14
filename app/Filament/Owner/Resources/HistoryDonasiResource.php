<?php

namespace App\Filament\Owner\Resources;

use App\Filament\Owner\Resources\HistoryDonasiResource\Pages;
use App\Models\Donasi;
use Filament\Forms;
use Filament\Forms\Form;
use Filament\Resources\Resource;
use Filament\Tables;
use Filament\Tables\Table;
use Filament\Tables\Filters\SelectFilter;
use Illuminate\Database\Eloquent\Builder;

class HistoryDonasiResource extends Resource
{
    protected static ?string $model = Donasi::class;

    protected static ?string $navigationIcon = 'heroicon-o-clock';

    protected static ?string $navigationLabel = 'History Donasi';

    protected static ?string $pluralModelLabel = 'History Donasi';

    protected static ?string $modelLabel = 'History Donasi';

    public static function canCreate(): bool
    {
        return false;
    }

    public static function form(Form $form): Form
    {
        return $form
            ->schema([
                Forms\Components\TextInput::make('id_donasi')
                    ->label('ID Donasi')
                    ->disabled(),

                Forms\Components\Select::make('id_request')
                    ->label('Request Donasi')
                    ->relationship('request', 'desk_request')
                    ->disabled(),

                Forms\Components\Select::make('kode_barang')
                    ->label('Barang')
                    ->relationship('barang', 'nama_barang')
                    ->disabled(),

                Forms\Components\Select::make('id_penitip')
                    ->label('Penitip')
                    ->relationship('penitip', 'nama_penitip')
                    ->disabled(),

                Forms\Components\TextInput::make('nama_penerima')
                    ->label('Nama Penerima')
                    ->disabled(),

                Forms\Components\DatePicker::make('tanggal_donasi')
                    ->label('Tanggal Donasi')
                    ->disabled(),

            ]);
    }

    public static function table(Table $table): Table
    {
        return $table
            ->columns([
                Tables\Columns\TextColumn::make('id_donasi')
                    ->label('ID Donasi')
                    ->sortable(),

                Tables\Columns\TextColumn::make('request.organisasi.nama_organisasi')
                    ->label('Organisasi')
                    ->sortable()
                    ->searchable(),

                // Tables\Columns\TextColumn::make('barang.nama_barang')
                //     ->label('Barang')
                //     ->searchable(),

                // Tables\Columns\TextColumn::make('penitip.nama_penitip')
                //     ->label('Penitip')
                //     ->searchable(),

                Tables\Columns\TextColumn::make('nama_penerima')
                    ->label('Penerima')
                    ->searchable(),

                Tables\Columns\TextColumn::make('tanggal_donasi')
                    ->label('Tanggal Donasi')
                    ->date()
                    ->sortable(),

              Tables\Columns\BadgeColumn::make('request.status')
                    ->label('Status')
                    ->colors([
                        'success' => fn ($state) => $state === 'Diterima',

                    ]),

                Tables\Columns\TextColumn::make('created_at')
                    ->label('Tanggal Dibuat')
                    ->dateTime()
                    ->sortable()
                    ->toggleable(isToggledHiddenByDefault: true),

                Tables\Columns\TextColumn::make('updated_at')
                    ->label('Terakhir Diupdate')
                    ->dateTime()
                    ->sortable()
                    ->toggleable(isToggledHiddenByDefault: true),
            ])
            ->filters([
                SelectFilter::make('status')
                    ->options([
                        'Diproses' => 'Diproses',
                        'Selesai' => 'Selesai',
                        'Ditolak' => 'Ditolak',
                    ]),

                SelectFilter::make('organisasi')
                    ->relationship('request.organisasi', 'nama_organisasi'),

                Tables\Filters\Filter::make('tanggal_donasi')
                    ->form([
                        Forms\Components\DatePicker::make('dari_tanggal')
                            ->label('Dari Tanggal'),
                        Forms\Components\DatePicker::make('sampai_tanggal')
                            ->label('Sampai Tanggal'),
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
            ->actions([
                Tables\Actions\ViewAction::make(),
            ])
            ->bulkActions([
                Tables\Actions\BulkActionGroup::make([
                ]),
            ]);
    }
    public static function getEloquentQuery(): Builder
    {
        return parent::getEloquentQuery()->whereHas('request', function ($query) {
            $query->where('status', 'Diterima');
        });
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
            'index' => Pages\ListHistoryDonasis::route('/'),
            'view' => Pages\ViewHistoryDonasi::route('/{record}'),
        ];
    }
}
