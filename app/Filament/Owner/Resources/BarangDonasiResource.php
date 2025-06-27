<?php

namespace App\Filament\Owner\Resources;

use App\Filament\Owner\Resources\BarangDonasiResource\Pages;
use App\Models\Barang;
use App\Models\RequestDonasi;
use Filament\Forms;
use Filament\Forms\Form;
use Filament\Resources\Resource;
use Filament\Tables;
use Filament\Tables\Table;
use Illuminate\Database\Eloquent\Builder;

class BarangDonasiResource extends Resource
{
    protected static ?string $model = Barang::class;
    protected static ?string $navigationIcon = 'heroicon-o-archive-box';
    protected static ?string $navigationGroup = 'Donasi Management';
    protected static ?string $navigationLabel = 'Barang Donasi';
    protected static ?int $navigationSort = 3;

    public static function getEloquentQuery(): Builder
    {
        return parent::getEloquentQuery()
            ->where('opsi', 'Didonasikan')
            ->where('status', 'Tersedia');
    }


    public static function form(Form $form): Form
    {
        return $form
            ->schema([
                Forms\Components\TextInput::make('kode_barang')
                    ->required()
                    ->maxLength(255)
                    ->disabled(),
                Forms\Components\TextInput::make('nama_barang')
                    ->default('-')
                    ->required()
                    ->maxLength(255)
                    ->disabled(),
                Forms\Components\Select::make('id_penitip')
                    ->relationship('penitip', 'nama_penitip')
                    ->disabled(),
                Forms\Components\Select::make('id_kategori')
                    ->relationship('kategori', 'nama_kategori')
                    ->disabled(),
                Forms\Components\Textarea::make('deskripsi')
                    ->disabled()
                    ->columnSpanFull(),
                Forms\Components\TextInput::make('status')
                    ->disabled(),
                Forms\Components\TextInput::make('opsi')
                    ->disabled(),
                Forms\Components\DatePicker::make('tanggal_masuk')
                    ->disabled(),
                Forms\Components\Section::make('Alokasikan ke Organisasi')
                    ->schema([
                        Forms\Components\Select::make('id_request')
                            ->label('Request Donasi')
                            ->options(function () {
                                return RequestDonasi::where('status', 'Diproses')
                                    ->pluck('desk_request', 'id_request');
                            })
                            ->required()
                            ->searchable()
                            ->reactive()
                            ->afterStateUpdated(function ($state, callable $set) {
                                if ($state) {
                                    $request = RequestDonasi::with('organisasi')->find($state);
                                    if ($request) {
                                        $set('organisasi', $request->organisasi->nama_organisasi);
                                    }
                                }
                            }),
                        Forms\Components\TextInput::make('organisasi')
                            ->label('Organisasi')
                            ->disabled(),
                        Forms\Components\DatePicker::make('tanggal_donasi')
                            ->required()
                            ->default(now()),
                        Forms\Components\TextInput::make('nama_penerima')
                            ->required()
                            ->maxLength(255),
                    ]),
            ]);
    }

    public static function table(Table $table): Table
    {
        return $table
            ->columns([
                Tables\Columns\TextColumn::make('kode_barang')
                    ->searchable(),
                Tables\Columns\TextColumn::make('nama_barang')
                    ->getStateUsing(fn ($record) => $record->nama_barang ?? '-')
                    ->searchable(),
                Tables\Columns\TextColumn::make('penitip.nama_penitip')
                    ->searchable(),
                Tables\Columns\TextColumn::make('kategori.nama_kategori')
                    ->searchable(),
                Tables\Columns\TextColumn::make('status')
                    ->badge()
                    ->color(fn (string $state): string => match ($state) {
                        'Tersedia' => 'success',
                        'Terjual' => 'primary',
                        'Didonasikan' => 'warning',
                        default => 'gray',
                    }),
                Tables\Columns\TextColumn::make('tanggal_masuk')
                    ->date()
                    ->sortable(),
            ])
            ->filters([
                Tables\Filters\SelectFilter::make('kategori')
                    ->relationship('kategori', 'nama_kategori'),
            ])
            ->actions([
                Tables\Actions\ViewAction::make(),
                Tables\Actions\Action::make('alokasikan')
                    ->label('Alokasikan')
                    ->icon('heroicon-o-gift')
                    ->color('success')
                    ->form([
                        Forms\Components\Select::make('id_request')
                            ->label('Request Donasi')
                            ->options(function () {
                                return RequestDonasi::where('status', 'Diproses')
                                    ->pluck('desk_request', 'id_request');
                            })
                            ->required()
                            ->searchable()
                            ->reactive()
                            ->afterStateUpdated(function ($state, callable $set) {
                                if ($state) {
                                    $request = RequestDonasi::with('organisasi')->find($state);
                                    if ($request) {
                                        $set('organisasi', $request->organisasi->nama_organisasi);
                                    }
                                }
                            }),
                        Forms\Components\TextInput::make('organisasi')
                            ->label('Organisasi')
                            ->disabled(),
                        Forms\Components\DatePicker::make('tanggal_donasi')
                            ->required()
                            ->default(now()),
                        Forms\Components\TextInput::make('nama_penerima')
                            ->required()
                            ->maxLength(255),
                    ])
                   ->action(function (array $data, Barang $record) {

                       $donasi = new \App\Models\Donasi();
                       $donasi->id_request = $data['id_request'];
                       $donasi->kode_barang = $record->kode_barang;
                       $donasi->id_penitip = $record->id_penitip;
                       $donasi->tanggal_donasi = $data['tanggal_donasi'];
                       $donasi->nama_penerima = $data['nama_penerima'];
                       $donasi->nama_penitip = $record->penitip->nama_penitip;
                       $donasi->save();

                       $request = RequestDonasi::find($data['id_request']);
                       if ($request) {
                           $request->status = 'Selesai';
                           $request->save();
                       }

                       $record->status = 'Didonasikan';
                       $record->tanggal_laku = now();
                       $record->save();

                       $penitip = $record->penitip;
                       if ($penitip) {
                           $hargaBarang = $record->harga;
                           $poinTambahan = floor($record->harga / 10000);
                           $penitip->poin += $poinTambahan;
                           $penitip->save();
                       }
                   })

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
            'index' => Pages\ListBarangDonasis::route('/'),
            'view' => Pages\ViewBarangDonasi::route('/{record}'),
        ];
    }
}
