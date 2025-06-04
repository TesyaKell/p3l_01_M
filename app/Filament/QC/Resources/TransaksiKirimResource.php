<?php

namespace App\Filament\QC\Resources;

use App\Filament\QC\Resources\TransaksiKirimResource\Pages;
use App\Filament\QC\Resources\TransaksiKirimResource\RelationManagers;
use App\Models\Barang;
use App\Models\DetailTransaksi;
use App\Models\Penitip;
use App\Models\Transaksi;
use App\Models\Pembeli;
use App\Notifications\MobileNotif;
use Filament\Tables\Actions\Action;
use Filament\Notifications\Notification;
use Filament\Forms;
use Filament\Forms\Components\DateTimePicker;
use Filament\Forms\Components\Select;
use Filament\Forms\Form;
use Filament\Resources\Resource;
use Filament\Tables;
use Filament\Tables\Table;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\SoftDeletingScope;

class TransaksiKirimResource extends Resource
{
    protected static ?string $model = Transaksi::class;

    protected static ?string $navigationIcon = 'heroicon-o-rectangle-stack';
    protected static ?string $navigationLabel = 'Transaksi Kirim/Ambil Barang';
    protected static ?string $navigationGroup = 'Manajemen Jadwal';
    public static function form(Form $form): Form
    {
        return $form
            ->schema([
                //
            ]);
    }

    public static function table(Table $table): Table
    {
        return $table
            ->columns([
                Tables\Columns\TextColumn::make('no_nota')->label('Nomor Nota')->searchable(),
                Tables\Columns\TextColumn::make('pegawai.nama_pegawai')->label('Kurir')->searchable()->placeholder('Belum di Assign'),
                Tables\Columns\TextColumn::make('pembeli.nama_pembeli')->label('Pembeli')->searchable(),
                Tables\Columns\TextColumn::make('tanggal_pesan')
                    ->label('Tanggal Pesan')
                    ->dateTime()
                    ->sortable()
                    ->limit(13)// comment untuk tampilkan full
                    ->tooltip(fn($record) => $record->tanggal_pesan)// comment untuk tampilkan full
                ,
                Tables\Columns\TextColumn::make('tanggal_lunas')
                    ->label('Tanggal Lunas')
                    ->dateTime()
                    ->sortable()
                    ->placeholder('Belum Lunas')
                    ->limit(13)// comment untuk tampilkan full
                    ->tooltip(fn($record) => $record->tanggal_lunas)// comment untuk tampilkan full
                ,
                Tables\Columns\TextColumn::make('tipe_delivery')
                    ->label('Tipe Pengiriman')
                    ->colors([
                        'primary' => 'ambil_tempat',
                        'info' => 'kurir',
                    ])->searchable(),
                Tables\Columns\TextColumn::make('tanggal_ambil_kirim')
                    ->label('Tanggal Ambil/Kirim')
                    ->dateTime()
                    ->sortable()
                    ->placeholder('Belum Dijadwalkan')
                    ->limit(13)// comment untuk tampilkan full
                    ->tooltip(fn($record) => $record->tanggal_ambil_kirim)// comment untuk tampilkan full
                ,


                // Tables\Columns\BadgeColumn::make('tambah_poin')->label('+ Poin')->color('success')->numeric(),
                // Tables\Columns\BadgeColumn::make('poin_sebelum')->label('Poin Sebelum')->color('info')->numeric(),
                // Tables\Columns\BadgeColumn::make('poin_setelah')->label('Poin Setelah')->color('info')->numeric(),

                Tables\Columns\BadgeColumn::make('status')
                    ->label('Status')
                    ->color(fn($state) => match ($state) {
                        'Selesai' => 'success',
                        'Dikirim', 'Disiapkan' => 'info',
                        'Menunggu Pickup', 'Menunggu Konfirmasi', 'Menunggu Pembayaran' => 'primary',
                        'Batal' => 'danger',
                        default => 'secondary',
                    })
                ,
                Tables\Columns\TextColumn::make('ongkir')->label('Ongkir')->money('IDR'),

                Tables\Columns\TextColumn::make('alamat_pengiriman')->label('Alamat')->placeholder('-'),

                Tables\Columns\TextColumn::make('total_harga_jual_bersih')->label('Total Harga Jual Bersih')->money('IDR'),
                Tables\Columns\TextColumn::make('bukti_pembayaran')
                    ->label('Bukti')
                    ->placeholder('-')
                    ->limit(20)//
                    ->tooltip(fn($record) => $record->bukti_pembayaran)//
                ,

                // Tables\Columns\TextColumn::make('komisi_penitip')->label('Komisi Penitip')->money('IDR'),

                Tables\Columns\TextColumn::make('total_pembayaran')->label('Total Pembayaran')->money('IDR'),
                // Tables\Columns\BadgeColumn::make('tukar_poin')->label('Tukar Poin')->color('danger')->placeholder('Tidak Menukar'),

            ])
            ->filters([
                Tables\Filters\SelectFilter::make('status')
                    ->options([
                        'Selesai' => 'Selesai',
                        'Menunggu Pickup' => 'Menunggu Pickup',
                        'Dikirim' => 'Dikirim',
                        'Disiapkan' => 'Disiapkan',
                        'Menunggu Konfirmasi' => 'Menunggu Konfirmasi',
                        'Menunggu Pembayaran' => 'Menunggu Pembayaran',
                        'Batal' => 'Batal',
                    ]),
            ])
            ->actions([
                Action::make('aturPengiriman')
                    ->label('Atur Pengiriman')
                    ->icon('heroicon-o-truck')
                    ->color('info')
                    ->form([
                        DateTimePicker::make('tanggal_ambil_kirim')
                            ->label('Jadwal Pengiriman')
                            ->required(),

                        Select::make('id_kurir_pegawai')
                            ->label('Pilih Kurir')
                            ->relationship(
                                'pegawai',
                                'nama_pegawai',
                                modifyQueryUsing: fn($query) => $query->where('kode_jabatan', 'J06')
                            ) // asumsi ada relasi ke model Kurir
                            ->searchable()
                            ->preload()
                            ->required(),
                    ])->action(function ($record, array $data) {
                        $record->tanggal_ambil_kirim = $data['tanggal_ambil_kirim'];
                        $record->id_kurir_pegawai = $data['id_kurir_pegawai'];
                        $record->status = 'Dikirim';
                        $record->save();

                        // Notifikasi untuk Kurir
                        $kurir = $record->pegawai;
                        if ($kurir) {
                            $kurir->notify(new MobileNotif(
                                'Jadwal Pengiriman Baru',
                                'Anda telah dijadwalkan mengirim barang untuk nota ' . $record->no_nota
                            ));
                        }

                        // Notifikasi untuk Pembeli
                        if ($record->pembeli) {
                            $record->pembeli->notify(new MobileNotif(
                                'Barang Sedang Dikirim',
                                'Barang pesanan Anda sedang dalam proses pengiriman.'
                            ));
                        }

                        // Notifikasi untuk Penitip dari setiap detail barang
                        $detailList = DetailTransaksi::where('no_nota', $record->no_nota)->get();
                        foreach ($detailList as $detail) {
                            $barang = Barang::where('kode_barang', $detail->kode_barang)->first();
                            if (!$barang)
                                continue;

                            $penitip = Penitip::find($barang->id_penitip);
                            if ($penitip) {
                                $penitip->notify(new MobileNotif(
                                    'Barang Anda Sedang Dikirim',
                                    'Barang Anda dalam nota ' . $record->no_nota . ' sedang dikirim oleh kurir.'
                                ));
                            }
                        }

                    })
                    ->modalHeading('Penjadwalan Pengiriman')
                    ->modalButton('Simpan')
                    ->requiresConfirmation()
                    ->visible(fn($record) => $record->tipe_delivery === 'kurir' and $record->status === 'Disiapkan'),
                //berhasil update tapi belum kirim notifikasi

                Action::make('aturPengambilan')
                    ->label('Atur Pengambilan')
                    ->icon('heroicon-o-truck')
                    ->color('info')
                    ->form([
                        DateTimePicker::make('tanggal_ambil_kirim')
                            ->label('Jadwal Pengambilan')
                            ->required(),
                    ])->action(function ($record, array $data) {
                        $record->tanggal_ambil_kirim = $data['tanggal_ambil_kirim'];
                        $record->status = 'Menunggu Pickup';
                        $record->save();

                        // Notifikasi untuk Pembeli
                        if ($record->pembeli) {
                            $record->pembeli->notify(new MobileNotif(
                                'Barang Siap Diambil',
                                'Silakan ambil barang pesanan Anda sesuai jadwal yang telah ditentukan.'
                            ));
                        }

                        // Notifikasi untuk Penitip dari setiap detail barang
                        $detailList = DetailTransaksi::where('no_nota', $record->no_nota)->get();
                        foreach ($detailList as $detail) {
                            $barang = Barang::where('kode_barang', $detail->kode_barang)->first();
                            if (!$barang)
                                continue;

                            $penitip = Penitip::find($barang->id_penitip);
                            if ($penitip) {
                                $penitip->notify(new MobileNotif(
                                    'Barang Anda Siap Diambil',
                                    'Barang Anda dalam nota ' . $record->no_nota . ' siap diambil oleh pembeli.'
                                ));
                            }
                        }

                    })
                    ->modalHeading('Penjadwalan Pengambilan')
                    ->modalButton('Simpan')
                    ->requiresConfirmation()
                    ->visible(fn($record) => $record->tipe_delivery === 'ambil_tempat' and $record->status === 'Disiapkan'),
                //berhasil update tapi belum kirim notifikasi

                Action::make('konfirmasiPengambilan')
                    ->label('Konfirmasi Pengambilan')
                    ->icon('heroicon-o-truck')
                    ->color('info')
                    ->requiresConfirmation()
                    ->action(function ($record) {
                        $record->status = 'Selesai';
                        $record->save();
                        $detailList = DetailTransaksi::where('no_nota', $record->no_nota)->get();
                        foreach ($detailList as $detail) {
                            $barang = Barang::where('kode_barang', $detail->kode_barang)->first();
                            if (!$barang)
                                continue;

                            $penitip = Penitip::where('id_penitip', $barang->id_penitip)->first();
                            if (!$penitip)
                                continue;

                            $penitip->update([
                                'saldo' => $penitip->saldo + $detail->komisi_penitip,
                            ]);
                        }
                        Notification::make()
                            ->title('Barang berhasil dikonfirmasi')
                            ->body('Status telah diperbarui menjadi Selesai.')
                            ->success()
                            ->send();
                        //notification hanya ke pegawai saja belum ke pembeli dan penitip
                    })
                    ->modalHeading('Konfirmasi Pengambilan Barang')
                    ->modalSubheading('Yakin ingin menandai barang ini sebagai telah selesai diambil?')
                    ->modalButton('Ya, Selesaikan')
                    ->requiresConfirmation()
                    ->visible(fn($record) => $record->status === 'Menunggu Pickup'),
                //berhasil update tapi belum kirim notifikasi
                Tables\Actions\Action::make('cetakNota')
                    ->label('Cetak Nota')
                    ->icon('heroicon-o-printer')
                    ->url(fn($record) => route('cetak-nota-penjualan', $record->no_nota))
                    ->openUrlInNewTab()
                    ->color('gray')
                    ->visible(fn($record) => $record->status !== 'Batal'),


                Tables\Actions\EditAction::make(),
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
            'index' => Pages\ListTransaksiKirims::route('/'),
            'create' => Pages\CreateTransaksiKirim::route('/create'),
            'edit' => Pages\EditTransaksiKirim::route('/{record}/edit'),
        ];
    }
}
