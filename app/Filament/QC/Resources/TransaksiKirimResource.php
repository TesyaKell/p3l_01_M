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
use Carbon\Carbon;
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
                                    'Barang Anda Akan Dikirim',
                                    'Barang Anda dalam nota ' . $record->no_nota . ' akan dikirim oleh kurir.'
                                ));
                            }


                        }
                        Notification::make()
                            ->title('Pengiriman dijadwalkan')
                            ->body('Pengiriman berhasil dijadwalkan dan semua pihak telah diberi notifikasi.')
                            ->success()
                            ->send();

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
                        $jadwal = \Carbon\Carbon::parse($data['tanggal_ambil_kirim']);
                        $now = \Carbon\Carbon::now();

                        // Jika sekarang lewat jam 4 sore dan tanggal pengiriman = hari ini, tolak
                        if ($now->format('H') >= 20 && $jadwal->isSameDay($now)) {
                            Notification::make()
                                ->title('Pengambilan tidak valid')
                                ->body('Pengambilan tidak bisa dijadwalkan di hari yang sama setelah jam 20:00.')
                                ->danger()
                                ->send();
                            return;
                        }

                        // Simpan data pengiriman
                        $record->tanggal_ambil_kirim = $jadwal;
                        $record->status = 'Menunggu Pickup';
                        $record->save();

                        // Notifikasi ke pembeli
                        if ($record->pembeli  && filled($record->pembeli->fcm_token)) {
                            $record->pembeli->notify(new MobileNotif(
                                title: 'Pengambilan Dijadwalkan',
                                body: 'Barang Anda dapat diambil pada tanggal' . $jadwal->translatedFormat('l, d F Y H:i')
                            ));
                        }

                        // Notifikasi ke semua penitip barang
                        foreach ($record->detailTransaksi as $detail) {
                            $barang = $detail->barang;
                            if ($barang && filled($barang->penitip->fcm_token)) {
                                $barang->penitip->notify(new MobileNotif(
                                    title: 'Barang Anda Akan Diambil',
                                    body: 'Barang "' . $barang->nama_barang . '" dapat diambil pada ' . $jadwal->translatedFormat('d F Y')
                                ));
                            }
                        }

                        Notification::make()
                            ->title('Pengambilan dijadwalkan')
                            ->body('Pengambilan berhasil dijadwalkan dan semua pihak telah diberi notifikasi.')
                            ->success()
                            ->send();
                    })
                    ->modalHeading('Penjadwalan Pengambilan')
                    ->modalButton('Simpan')
                    ->requiresConfirmation()
                    ->visible(fn ($record) => $record->tipe_delivery === 'ambil_tempat' && $record->status === 'Disiapkan'),

                Action::make('konfirmasiPengambilan')
                ->label('Konfirmasi Pengambilan')
                ->icon('heroicon-o-truck')
                ->color('info')
                ->requiresConfirmation()
                ->action(function ($record) {                    
                    $detailList = DetailTransaksi::where('no_nota', $record->no_nota)->get();
                    
                    $totalHarga = 0;
                    $totalBonus = 0;
                    
                    foreach ($detailList as $detail) {
                        $barang = Barang::where('kode_barang', $detail->kode_barang)->first();
                        if (!$barang) continue;
                        
                        $total_harga_barang = $barang->harga;
                        if ($barang->opsi_barang === 'Diperpanjang' && $barang->id_hunter_pegawai) {
                            $komisiReusmart = $total_harga_barang * 0.25;
                            $komisiPenitip = $total_harga_barang * 0.70;
                        } elseif ($barang->opsi_barang === 'Diperpanjang') {
                            $komisiReusmart = $total_harga_barang * 0.30;
                            $komisiPenitip = $total_harga_barang * 0.70;
                        } elseif ($barang->id_hunter_pegawai) {
                            $komisiReusmart = $total_harga_barang * 0.15;
                            $komisiPenitip = $total_harga_barang * 0.80;
                        } else {
                            $komisiReusmart = $total_harga_barang * 0.20;
                            $komisiPenitip = $total_harga_barang * 0.80;
                        }
                        
                        $komisiHunter = $barang->id_hunter_pegawai ? $total_harga_barang * 0.05 : 0;
                        $hargaJualBersih = $total_harga_barang - $komisiReusmart - $komisiHunter;
                        
                        $tanggalMasuk = Carbon::parse($barang->tanggal_masuk);
                        $tanggalLaku = Carbon::parse($barang->tanggal_laku);
                        $selisihHari = $tanggalMasuk->diffInDays($tanggalLaku, false);
                        $bonus = ($selisihHari >= 0 && $selisihHari < 7) ? $komisiReusmart * 0.1 : 0;
                        
                        $detail->update ([
                            'harga_jual_bersih' => $hargaJualBersih,
                            'komisi_reusmart' => $komisiReusmart,
                            'komisi_hunter' => $komisiHunter,
                            'bonus' => $bonus,
                            'total' => $hargaJualBersih + $bonus,
                            'komisi_penitip' => $komisiPenitip + $bonus,
                        ]);
                        $totalHarga += $barang->harga;
                        $totalBonus += $bonus;
                        
                        $penitip = Penitip::find($barang->id_penitip);
                        if ($penitip && filled($penitip->fcm_token)) {
                            // Update saldo
                            $penitip->update([
                                'saldo' => $penitip->saldo + $detail->komisi_penitip,
                            ]);
                            
                            // Kirim notifikasi ke Penitip
                            $penitip->notify(new MobileNotif(
                                title: 'Barang Anda Terjual!',
                                body: 'Barang "' . $barang->nama_barang . '" telah berhasil dibeli.'
                            ));
                        }
                    }
                    $pembeli = Pembeli::where('id_pembeli', $record->id_pembeli)->first();

                    $poinSebelum = $pembeli->poin ?? 0;
                    $poinDasar = floor($totalHarga / 10000);
                    $bonusPoin = $totalHarga > 500000 ? floor($poinDasar * 0.2) : 0;
                    $totalPoinDapat = $poinDasar + $bonusPoin;

                    $tukarPoin = $record->tukar_poin;
                    $poinSetelah = max(0, $poinSebelum + $totalPoinDapat - $tukarPoin);

                    $record->poin_sebelum = $poinSebelum;
                    $record->tambah_poin = $totalPoinDapat;
                    $record->poin_setelah = $poinSetelah;
                    $record->tukar_poin = $tukarPoin;
                    
                    $pembeli->update([
                        'poin' => $poinSetelah
                    ]);

                    // Notifikasi ke Pembeli (jika relasi tersedia)
                    if ($record->pembeli && filled($record->pembeli->fcm_token)) {
                        $record->pembeli->notify(new MobileNotif(
                            title: 'Pengambilan Berhasil!',
                            body: 'Barang pesanan Anda telah dikonfirmasi sebagai berhasil diambil.'
                        ));
                    }
                    $record->status = 'Selesai';
                    $record->save();
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
                ->visible(fn ($record) =>  $record->status === 'Menunggu Pickup'),

      //berhasil update tapi belum kirim notifikasi
                Tables\Actions\Action::make('cetakNota')
                    ->label('Cetak Nota')
                    ->icon('heroicon-o-printer')
                    ->url(fn($record) => route('cetak-nota-penjualan', $record->no_nota))
                    ->openUrlInNewTab()
                    ->color('gray')
                    ->visible(fn($record) => $record->status !== 'Batal'),


                // Tables\Actions\EditAction::make(),
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
