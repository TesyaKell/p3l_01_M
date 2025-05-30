<?php

namespace App\Filament\QC\Resources;

use App\Filament\QC\Resources\BarangResource\Pages;
use App\Models\Barang;
use App\Models\Pegawai;
use App\Models\Transaksi;
use App\Models\KategoriBarang;
use Filament\Forms;
use Filament\Forms\Form;
use Filament\Resources\Resource;
use Filament\Tables;
use Filament\Tables\Table;
use Illuminate\Database\Eloquent\Builder;
use App\Models\Penitip;
use Filament\Infolists;
use Filament\Infolists\Infolist;
use Filament\Support\Enums\FontWeight;
use Filament\Notifications\Notification;
use Barryvdh\DomPDF\Facade\Pdf;
use Carbon\Carbon;
use Filament\Actions;

class BarangResource extends Resource
{
    protected static ?string $model = Barang::class;

    protected static ?string $navigationIcon = 'heroicon-o-archive-box';

    protected static ?string $navigationLabel = 'Barang Titipan';

    protected static ?string $navigationGroup = 'Manajemen Barang';

    public static function form(Form $form): Form
    {
        return $form
            ->schema([
               Forms\Components\Select::make('id_penitip')
                ->label('Penitip')
                ->options(Penitip::pluck('nama_penitip', 'id_penitip')->toArray())
                ->searchable()
                ->required()
                ->preload()
                ->disabled(fn ($livewire) => $livewire->getRecord() !== null)
                ->columnSpanFull(),


                Forms\Components\Repeater::make('barangs')
                    ->label('Daftar Barang')
                    ->schema([
                        Forms\Components\TextInput::make('kode_barang')
                            ->maxLength(255)
                            ->hidden()
                            ->disabled(),

                        Forms\Components\Select::make('id_kategori')
                            ->label('Kategori')
                            ->options(KategoriBarang::pluck('nama_kategori', 'id_kategori'))
                            ->searchable()
                            ->required()
                            ->preload(),

                        Forms\Components\Select::make('id_qc_pegawai')
                            ->label('Nama Pegawai Quality Control')
                            ->options(
                                Pegawai::where('kode_jabatan', 'J04')
                                    ->pluck('nama_pegawai', 'id_pegawai')
                            )
                            ->searchable()
                            ->required()
                            ->preload(),

                        Forms\Components\Select::make('id_hunter_pegawai')
                            ->label('Nama Pegawai Hunter')
                            ->options(
                                Pegawai::where('kode_jabatan', 'J03')
                                    ->pluck('nama_pegawai', 'id_pegawai')
                            )
                            ->searchable()
                            ->required()
                            ->preload(),

                        Forms\Components\TextInput::make('nama_barang')
                            ->label('Nama Barang')
                            ->nullable(),

                        Forms\Components\Select::make('garansi')
                            ->label('Tersedia Garansi')
                            ->options([
                                true => 'Ya',
                                false => 'Tidak',
                            ])
                            ->default(false)
                            ->required()
                            ->reactive()
                            ->afterStateUpdated(function ($state, \Filament\Forms\Set $set, $get) {
                                if ($state) {
                                    $tanggalMasuk = $get('tanggal_masuk');
                                    if ($tanggalMasuk) {
                                        $set('batas_garansi', Carbon::parse($tanggalMasuk)->addDays(30)->toDateTimeString());
                                    }
                                } else {
                                    $set('batas_garansi', null);
                                }
                            }),

                        Forms\Components\TextInput::make('status')
                            ->label('Status')
                            ->readOnly()
                            ->default('Tersedia')
                            ->required(),

                        Forms\Components\Textarea::make('deskripsi')
                            ->label('Deskripsi')
                            ->required()
                            ->columnSpanFull(),

                        Forms\Components\TextInput::make('opsi')
                            ->hidden(),

                       Forms\Components\DateTimePicker::make('tanggal_masuk')
                            ->label('Tanggal Masuk')
                            ->required()
                            ->default(now())
                            ->reactive()
                            ->afterStateUpdated(function ($state, \Filament\Forms\Set $set, $get) {
                                $tanggalMasuk = Carbon::now();
                                $tanggalBatas = $tanggalMasuk->copy()->addDays(30);
                                $tanggalAkhir = $tanggalBatas->copy();  // sama dengan tanggal_batas

                                $set('tanggal_batas', $tanggalBatas->toDateTimeString());
                                $set('tanggal_akhir', $tanggalAkhir->toDateTimeString());

                                if ($get('garansi')) {
                                    $set('batas_garansi', $tanggalBatas->toDateTimeString());
                                } else {
                                    $set('batas_garansi', null);
                                }
                            }),

                        Forms\Components\DateTimePicker::make('tanggal_akhir')
                            ->label('Tanggal Akhir')
                            ->readOnly()
                            ->afterStateHydrated(function ($state, \Filament\Forms\Set $set, $get) {
                                if ($get('tanggal_masuk')) {
                                    $tanggalBatas = Carbon::parse($get('tanggal_masuk'))->addDays(30);
                                    $tanggalAkhir = $tanggalBatas->copy();  // sama dengan tanggal_batas
                                    $set('tanggal_akhir', $tanggalAkhir->toDateTimeString());
                                }
                            }),

                        Forms\Components\DateTimePicker::make('tanggal_batas')
                            ->label('Tanggal Batas')
                            ->readOnly()
                            ->afterStateHydrated(function ($state, \Filament\Forms\Set $set, $get) {
                                if ($get('tanggal_masuk')) {
                                    $set('tanggal_batas', Carbon::parse($get('tanggal_masuk'))->addDays(30)->toDateTimeString());
                                }
                            }),

                        Forms\Components\DateTimePicker::make('batas_garansi')
                            ->label('Batas Garansi')
                            ->readOnly()
                            ->afterStateHydrated(function ($state, \Filament\Forms\Set $set, \Filament\Forms\Get $get) {
                                $tanggalMasuk = $get('tanggal_masuk');
                                $garansi = $get('garansi');
                                if ($garansi && $tanggalMasuk) {
                                    $set('batas_garansi', Carbon::parse($tanggalMasuk)->addDays(30)->toDateTimeString());
                                } else {
                                    $set('batas_garansi', null);
                                }
                            }),

                        Forms\Components\TextInput::make('harga')
                            ->label('Harga (Rp)')
                            ->numeric()
                            ->default(0),

                        Forms\Components\TextInput::make('berat_barang')
                            ->label('Berat Barang (Kg)')
                            ->numeric()
                            ->default(0)
                            ->required(),

                        Forms\Components\TextInput::make('durasi_penitipan')
                            ->label('Durasi Penitipan (Hari)')
                            ->default(30)
                            ->disabled(),

                        Forms\Components\FileUpload::make('foto_produk')
                            ->label('Gambar Barang')
                            ->multiple()
                            ->image()
                            ->maxFiles(5)
                            ->required(),
                    ])
                    ->required()
                    ->minItems(1)
                    ->maxItems(10)
                    ->addActionLabel('Tambah Barang')
                    ->collapsible()
                    ->columnSpanFull()

            ])
            ->statePath('data');
    }
    public static function table(Table $table): Table
    {
        return $table
            ->columns([
                //Tables\Columns\TextColumn::make('kategori.nama_kategori')->label('Kategori')->searchable(),
                Tables\Columns\TextColumn::make('penitip.nama_penitip')->label('Penitip')->searchable(),
                Tables\Columns\TextColumn::make('hunter.nama_pegawai')->label('Hunter')->searchable(),
                Tables\Columns\TextColumn::make('qcPegawai.nama_pegawai')->label('QC Pegawai')->searchable(),
                Tables\Columns\TextColumn::make('nama_barang')->label('Nama Barang')->searchable(),
                // Tables\Columns\TextColumn::make('no_nota')
                //     ->label('No Nota')
                //     ->getStateUsing(function ($record) {
                //         $detail = $record->detailTransaksi->first();
                //         return $detail && $detail->transaksi ? $detail->transaksi->no_nota : null;
                //     })
                //     ->sortable()
                //     ->searchable(),
                Tables\Columns\TextColumn::make('deskripsi')->label('Deskripsi')->limit(50),
                Tables\Columns\BadgeColumn::make('status')
                    ->label('Status')
                    ->colors([
                        'danger' => 'Terdonasi',
                        'success' => 'Terjual',
                        'primary' => 'Diambil',
                        'info' => 'Tersedia',
                    ])
                    ->searchable(),
                Tables\Columns\TextColumn::make('durasi_penitipan')
                    ->label('Durasi Penitipan')
                    ->getStateUsing(function ($record) {
                        $start = Carbon::parse($record->tanggal_masuk);
                        $end = Carbon::parse($record->tanggal_akhir);
                        $diff = $start->diffInDays($end);
                        return "{$diff} hari";
                    }),
                Tables\Columns\TextColumn::make('opsi')->label('Opsi'),
                Tables\Columns\TextColumn::make('harga')->label('Harga (Rp)')->money('IDR'),
                Tables\Columns\BooleanColumn::make('garansi')->label('Garansi'),
                Tables\Columns\TextColumn::make('tanggal_masuk')->label('Tanggal Masuk')->dateTime()->sortable(),
                // Tables\Columns\TextColumn::make('tanggal_akhir')->label('Tanggal Akhir')->dateTime()->sortable(),
                Tables\Columns\TextColumn::make('tanggal_batas')->label('Tanggal Batas')->dateTime()->sortable(),
                //Tables\Columns\TextColumn::make('tanggal_laku')->label('Tanggal Laku')->dateTime()->sortable()->placeholder('Belum Laku'),

                //Tables\Columns\TextColumn::make('tanggal_ambil')->label('Tanggal Ambil')->dateTime()->sortable()->placeholder('Belum diambil'),
                //Tables\Columns\TextColumn::make('berat_barang')->label('Berat (Kg)')->numeric(),
                Tables\Columns\TextColumn::make('batas_garansi')->label('Batas Garansi')->dateTime()->sortable(),
            ])
            ->filters([
                Tables\Filters\SelectFilter::make('status')
                    ->options([
                        'Tersedia' => 'Tersedia',
                        'Diambil' => 'Diambil',
                        'Terdonasi' => 'Terdonasi',
                        'Terjual' => 'Terjual',
                    ]),
                Tables\Filters\Filter::make('tanggal_masuk')
                    ->form([
                        Forms\Components\DatePicker::make('dari_tanggal'),
                        Forms\Components\DatePicker::make('sampai_tanggal'),
                    ])
                    ->query(function (Builder $query, array $data): Builder {
                        return $query
                            ->when($data['dari_tanggal'], fn ($query, $date) => $query->whereDate('tanggal_masuk', '>=', $date))
                            ->when($data['sampai_tanggal'], fn ($query, $date) => $query->whereDate('tanggal_masuk', '<=', $date));
                    }),
            ])
            ->actions([
                Tables\Actions\ViewAction::make(),
                Tables\Actions\EditAction::make()->disabled(fn ($record) => in_array($record->status, ['Terjual', 'Terdonasi'])), // Disable tombol edit
                Tables\Actions\Action::make('cetak_nota')
                    ->label('Cetak Nota')
                    ->icon('heroicon-o-printer')
                    ->url(fn ($record) => route('cetak-nota-titipan', ['barang' => $record->kode_barang]))


            ])
            ->bulkActions([
                Tables\Actions\BulkActionGroup::make([
                    Tables\Actions\DeleteBulkAction::make(),
                ]),
            ]);
    }

    public static function infolist(Infolist $infolist): Infolist
    {
        return $infolist
            ->schema([
                Infolists\Components\Section::make('📦 Informasi Barang')
                    ->schema([
                        Infolists\Components\TextEntry::make('nama_barang')
                            ->label('Nama Barang')
                            ->weight(FontWeight::Bold)
                            ->size(Infolists\Components\TextEntry\TextEntrySize::Large),
                        Infolists\Components\Grid::make(2)->schema([
                            Infolists\Components\TextEntry::make('kategori.nama_kategori')->label('Kategori'),
                            Infolists\Components\TextEntry::make('hunter.nama_pegawai')->label('Hunter'),
                            Infolists\Components\TextEntry::make('qcPegawai.nama_pegawai')->label('QC Pegawai'),
                            Infolists\Components\TextEntry::make('opsi')->label('Opsi'),
                            Infolists\Components\TextEntry::make('harga')->label('Harga')->money('IDR'),
                            Infolists\Components\IconEntry::make('garansi')->label('Garansi')->boolean(),
                            Infolists\Components\TextEntry::make('berat_barang')->label('Berat (Kg)')->numeric(),
                            Infolists\Components\TextEntry::make('no_nota')
                                ->label('No Nota')
                                ->getStateUsing(function ($record) {
                                    $detail = $record->detailTransaksi->first();
                                    return $detail && $detail->transaksi ? $detail->transaksi->no_nota : 'Barang Belum Terjual';
                                }),
                        ]),
                        Infolists\Components\TextEntry::make('deskripsi')
                            ->label('Deskripsi')
                            ->markdown()
                            ->columnSpanFull(),
                        Infolists\Components\ImageEntry::make('foto_produk')
                            ->label('Foto Produk')
                            ->columnSpanFull(),
                    ])
                    ->collapsible(),

                Infolists\Components\Section::make('📅 Status & Tanggal')
                    ->schema([
                        Infolists\Components\Grid::make(2)->schema([
                            Infolists\Components\TextEntry::make('status')
                                ->label('Status')
                                ->badge()
                                ->color(fn (string $state): string => match ($state) {
                                    'Terdonasi' => 'danger',
                                    'Terjual' => 'success',
                                    'Diambil' => 'primary',
                                    'Tersedia' => 'info',
                                    default => 'gray',
                                }),
                            Infolists\Components\TextEntry::make('durasi_penitipan')
                                ->label('Durasi Penitipan')
                                ->getStateUsing(function ($record) {
                                    if (!$record->tanggal_masuk || !$record->tanggal_akhir) {
                                        return '-';
                                    }
                                    $start = Carbon::parse($record->tanggal_masuk);
                                    $end = Carbon::parse($record->tanggal_akhir);
                                    $days = $start->diffInDays($end);
                                    return "{$days} hari";
                                })
                                ->icon('heroicon-m-clock'),
                            Infolists\Components\TextEntry::make('tanggal_masuk')
                                ->label('Tanggal Masuk')
                                ->icon('heroicon-m-calendar-days')
                                ->dateTime(),
                            Infolists\Components\TextEntry::make('tanggal_akhir')
                                ->label('Tanggal Akhir')
                                ->icon('heroicon-m-calendar-days')
                                ->dateTime(),
                            Infolists\Components\TextEntry::make('tanggal_batas')
                                ->label('Tanggal Batas')
                                ->icon('heroicon-m-calendar-days')
                                ->dateTime(),
                            Infolists\Components\TextEntry::make('tanggal_laku')
                                ->label('Tanggal Laku')
                                ->icon('heroicon-m-calendar-days')
                                ->dateTime()
                                ->placeholder('Belum Laku'),
                            Infolists\Components\TextEntry::make('tanggal_ambil')
                                ->label('Tanggal Ambil')
                                ->icon('heroicon-m-calendar-days')
                                ->dateTime()
                                ->placeholder('Belum diambil'),
                            Infolists\Components\TextEntry::make('batas_garansi')
                                ->label('Batas Garansi')
                                ->icon('heroicon-m-calendar-days')
                                ->dateTime(),
                        ]),
                    ])
                    ->collapsible(),

                Infolists\Components\Section::make('🙋‍♂️ Informasi Penitip')
                    ->schema([
                        Infolists\Components\Grid::make(2)->schema([
                            Infolists\Components\TextEntry::make('penitip.nama_penitip')->label('Nama Penitip'),
                            Infolists\Components\TextEntry::make('penitip.no_telp')->label('Nomor Telepon'),
                            Infolists\Components\TextEntry::make('penitip.email')->label('Email'),
                        ]),
                    ])
                    ->collapsible(),
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
            'index' => Pages\ListBarangs::route('/'),
            'create' => Pages\CreateBarang::route('/create'),
            'edit' => Pages\EditBarang::route('/{record}/edit'),
            'view' => Pages\ViewBarang::route('/{record}'),
        ];
    }
}
