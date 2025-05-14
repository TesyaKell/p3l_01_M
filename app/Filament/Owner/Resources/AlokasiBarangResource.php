<?php

namespace App\Filament\Owner\Resources;

use App\Filament\Owner\Resources\AlokasiBarangResource\Pages;
use App\Models\Barang;
use App\Models\Donasi;
use App\Models\RequestDonasi;
use App\Models\Organisasi;
use Filament\Forms;
use Filament\Forms\Form;
use Filament\Resources\Resource;
use Filament\Tables;
use Filament\Tables\Table;
use Filament\Tables\Filters\SelectFilter;
use Illuminate\Database\Eloquent\Builder;
use Filament\Notifications\Notification;
use Illuminate\Support\Facades\DB;

class AlokasiBarangResource extends Resource
{
    protected static ?string $model = Barang::class;

    protected static ?string $navigationIcon = 'heroicon-o-archive-box';

    protected static ?string $navigationLabel = 'Alokasi Barang Donasi';

    protected static ?string $pluralModelLabel = 'Alokasi Barang Donasi';

    protected static ?string $modelLabel = 'Alokasi Barang';

    // Hanya tampilkan barang dengan status "barang untuk donasi"
    public static function getEloquentQuery(): Builder
    {
        // Log untuk debugging
        \Illuminate\Support\Facades\Log::info('Status barang yang ada di database: ' .
            \App\Models\Barang::distinct()->pluck('status')->implode(', '));

        // Cek apakah ada barang dengan status 'barang untuk donasi'
        $countBarangDonasi = \App\Models\Barang::where('status', 'barang untuk donasi')->count();
        \Illuminate\Support\Facades\Log::info('Jumlah barang dengan status "barang untuk donasi": ' . $countBarangDonasi);

        // Jika tidak ada barang dengan status 'barang untuk donasi', tampilkan semua barang
        // yang statusnya mengandung kata 'donasi' atau yang tersedia
        if ($countBarangDonasi === 0) {
            return parent::getEloquentQuery()->where(function ($query) {
                $query->where('status', 'like', '%donasi%')
                      ->orWhere('status', 'tersedia')
                      ->orWhere('status', 'available');
            });
        }

        // Jika ada, gunakan filter asli
        return parent::getEloquentQuery()->where('status', 'barang untuk donasi');
    }

    // Tambahkan metode untuk menampilkan pesan kosong yang lebih informatif

    public static function getNavigationBadge(): ?string
    {
        return static::getEloquentQuery()->count();
    }

    public static function getEmptyStateHeading(): string
    {
        return 'Tidak ada barang untuk donasi';
    }

    public static function getEmptyStateDescription(): string
    {
        $statuses = \App\Models\Barang::distinct()->pluck('status')->implode(', ');
        return "Tidak ditemukan barang dengan status 'barang untuk donasi'. Status barang yang ada: {$statuses}";
    }

    public static function getEmptyStateActions(): array
    {
        return [
            \Filament\Actions\Action::make('check_barang')
                ->label('Periksa Semua Barang')
                ->url(route('filament.admin.resources.barangs.index'))
                ->icon('heroicon-o-arrow-right'),
        ];
    }

    public static function form(Form $form): Form
    {
        return $form
            ->schema([
                Forms\Components\TextInput::make('kode_barang')
                    ->label('Kode Barang')
                    ->disabled(),

                Forms\Components\TextInput::make('nama_barang')
                    ->label('Nama Barang')
                    ->disabled(),

                Forms\Components\TextInput::make('deskripsi')
                    ->label('Deskripsi')
                    ->disabled(),

                Forms\Components\TextInput::make('harga')
                    ->label('Nilai Barang (Rp)')
                    ->disabled(),

                Forms\Components\Select::make('id_penitip')
                    ->label('Penitip')
                    ->relationship('penitip', 'nama_penitip')
                    ->disabled(),

                Forms\Components\Select::make('id_request')
                    ->label('Alokasikan ke Request Donasi')
                    ->options(function () {
                        return RequestDonasi::where('status', 'Pending')
                            ->orWhere('status', 'Diproses')
                            ->get()
                            ->pluck('desk_request', 'id_request');
                    })
                    ->searchable()
                    ->required(),

                Forms\Components\TextInput::make('nama_penerima')
                    ->label('Nama Penerima')
                    ->required(),

                Forms\Components\DatePicker::make('tanggal_donasi')
                    ->label('Tanggal Donasi')
                    ->required(),
            ]);
    }

    public static function table(Table $table): Table
    {
        return $table
            ->columns([
                Tables\Columns\TextColumn::make('kode_barang')
                    ->label('Kode Barang')
                    ->searchable()
                    ->sortable(),

                Tables\Columns\TextColumn::make('nama_barang')
                    ->label('Nama Barang')
                    ->searchable()
                    ->sortable(),

                Tables\Columns\TextColumn::make('deskripsi')
                    ->label('Deskripsi')
                    ->limit(30),

                Tables\Columns\TextColumn::make('harga')
                    ->label('Nilai (Rp)')
                    ->money('IDR')
                    ->sortable(),

                Tables\Columns\TextColumn::make('penitip.nama_penitip')
                    ->label('Penitip')
                    ->searchable(),

                Tables\Columns\BadgeColumn::make('status')
                    ->label('Status')
                    ->colors([
                        'warning' => 'barang untuk donasi',
                    ]),

                Tables\Columns\TextColumn::make('tanggal_masuk')
                    ->label('Tanggal Masuk')
                    ->date()
                    ->sortable(),
            ])
            ->filters([
                SelectFilter::make('penitip')
                    ->relationship('penitip', 'nama_penitip')
                    ->searchable(),

                SelectFilter::make('kategori')
                    ->relationship('kategori', 'nama_kategori')
                    ->searchable(),
            ])
            ->actions([
                Tables\Actions\ViewAction::make(),

                Tables\Actions\Action::make('alokasi')
                    ->label('Alokasikan')
                    ->icon('heroicon-o-gift')
                    ->color('success')
                    ->form([
                        Forms\Components\Select::make('id_request')
                            ->label('Request Donasi')
                            ->options(function () {
                                return RequestDonasi::where('status', 'Pending')
                                    ->orWhere('status', 'Diproses')
                                    ->get()
                                    ->pluck('desk_request', 'id_request');
                            })
                            ->searchable()
                            ->required(),

                        Forms\Components\TextInput::make('nama_penerima')
                            ->label('Nama Penerima')
                            ->required(),

                        Forms\Components\DatePicker::make('tanggal_donasi')
                            ->label('Tanggal Donasi')
                            ->required(),
                    ])
                    ->action(function (Barang $record, array $data) {
                        DB::beginTransaction();

                        try {
                            // Buat record donasi baru
                            $donasi = new Donasi();
                            $donasi->id_request = $data['id_request'];
                            $donasi->kode_barang = $record->kode_barang;
                            $donasi->id_penitip = $record->id_penitip;
                            $donasi->nama_penerima = $data['nama_penerima'];
                            $donasi->tanggal_donasi = $data['tanggal_donasi'];
                            $donasi->status = 'Diproses';
                            $donasi->save();

                            // Update status barang menjadi "diproses untuk donasi"
                            $record->status = 'diproses untuk donasi';
                            $record->save();

                            // Update status request donasi jika masih pending
                            $request = RequestDonasi::find($data['id_request']);
                            if ($request && $request->status === 'Pending') {
                                $request->status = 'Diproses';
                                $request->save();
                            }

                            // Kirim notifikasi ke penitip jika ada
                            $penitip = $record->penitip;
                            if ($penitip && method_exists($penitip, 'notify')) {
                                $penitip->notify(new \App\Notifications\DonasiNotification(
                                    'Barang Dialokasikan untuk Donasi',
                                    "Barang {$record->nama_barang} telah dialokasikan untuk donasi."
                                ));
                            }

                            DB::commit();

                            Notification::make()
                                ->title('Barang Berhasil Dialokasikan')
                                ->success()
                                ->send();

                        } catch (\Exception $e) {
                            DB::rollBack();

                            Notification::make()
                                ->title('Gagal Mengalokasikan Barang')
                                ->body('Terjadi kesalahan: ' . $e->getMessage())
                                ->danger()
                                ->send();
                        }
                    }),
            ])
            ->bulkActions([
                Tables\Actions\BulkActionGroup::make([
                    Tables\Actions\BulkAction::make('alokasi_massal')
                        ->label('Alokasi Massal')
                        ->icon('heroicon-o-gift')
                        ->color('success')
                        ->form([
                            Forms\Components\Select::make('id_request')
                                ->label('Request Donasi')
                                ->options(function () {
                                    return RequestDonasi::where('status', 'Pending')
                                        ->orWhere('status', 'Diproses')
                                        ->get()
                                        ->pluck('desk_request', 'id_request');
                                })
                                ->searchable()
                                ->required(),

                            Forms\Components\TextInput::make('nama_penerima')
                                ->label('Nama Penerima')
                                ->required(),

                            Forms\Components\DatePicker::make('tanggal_donasi')
                                ->label('Tanggal Donasi')
                                ->required(),
                        ])
                        ->action(function (array $records, array $data) {
                            DB::beginTransaction();

                            try {
                                $successCount = 0;

                                foreach ($records as $record) {
                                    // Buat record donasi baru
                                    $donasi = new Donasi();
                                    $donasi->id_request = $data['id_request'];
                                    $donasi->kode_barang = $record->kode_barang;
                                    $donasi->id_penitip = $record->id_penitip;
                                    $donasi->nama_penerima = $data['nama_penerima'];
                                    $donasi->tanggal_donasi = $data['tanggal_donasi'];
                                    $donasi->status = 'Diproses';
                                    $donasi->save();

                                    // Update status barang
                                    $record->status = 'diproses untuk donasi';
                                    $record->save();

                                    // Kirim notifikasi ke penitip
                                    $penitip = $record->penitip;
                                    if ($penitip && method_exists($penitip, 'notify')) {
                                        $penitip->notify(new \App\Notifications\DonasiNotification(
                                            'Barang Dialokasikan untuk Donasi',
                                            "Barang {$record->nama_barang} telah dialokasikan untuk donasi."
                                        ));
                                    }

                                    $successCount++;
                                }

                                // Update status request donasi jika masih pending
                                $request = RequestDonasi::find($data['id_request']);
                                if ($request && $request->status === 'Pending') {
                                    $request->status = 'Diproses';
                                    $request->save();
                                }

                                DB::commit();

                                Notification::make()
                                    ->title("$successCount Barang Berhasil Dialokasikan")
                                    ->success()
                                    ->send();

                            } catch (\Exception $e) {
                                DB::rollBack();

                                Notification::make()
                                    ->title('Gagal Mengalokasikan Barang')
                                    ->body('Terjadi kesalahan: ' . $e->getMessage())
                                    ->danger()
                                    ->send();
                            }
                        }),
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
            'index' => Pages\ListAlokasiBarangs::route('/'),
            'view' => Pages\ViewAlokasiBarang::route('/{record}'),
        ];
    }
}
