<?php

namespace App\Filament\Owner\Resources;

use App\Models\Barang;
use Filament\Resources\Resource;
use Filament\Tables\Table;
use Filament\Tables\Columns\TextColumn;
use Filament\Tables\Actions\Action;
use Barryvdh\DomPDF\Facade\Pdf;
use Carbon\Carbon;
use Filament\Forms;

use App\Filament\Owner\Resources\PrintLaporanTransaksiPenitipResource\Pages;


class PrintLaporanTransaksiPenitipResource extends Resource
{
    protected static ?string $model = Barang::class;

    protected static ?string $navigationIcon = 'heroicon-o-rectangle-stack';
    protected static ?string $navigationLabel = 'Laporan Transaksi Penitip';
    protected static ?string $navigationGroup = 'Laporan';

    public static function table(Table $table): Table
    {
        return $table
            ->columns([
                TextColumn::make('penitip.nama_penitip')->label('Nama Penitip')->default('-'),
                TextColumn::make('kode_barang')->label('Kode Barang')->sortable(),
                TextColumn::make('nama_barang')->label('Nama Barang')->default('-'),
                TextColumn::make('detailTransaksi.harga_jual_bersih')->label('Harga Bersih')->default('-'),
                TextColumn::make('detailTransaksi.bonus')->label('Bonus')->default('-'),
                TextColumn::make('detailTransaksi.total')->label('Total')->default('-'),
                TextColumn::make('tanggal_laku')->label('Tanggal Laku')->date(),
            ])
            ->actions([]); // hapus tombol aksi di baris tabel
    }


    public static function getPages(): array
    {
        return [
            'index' => Pages\ListPrintLaporanTransaksiPenitips::route('/'),
            'create' => Pages\CreatePrintLaporanTransaksiPenitip::route('/create'),
            'edit' => Pages\EditPrintLaporanTransaksiPenitip::route('/{record}/edit'),
        ];
    }

}
