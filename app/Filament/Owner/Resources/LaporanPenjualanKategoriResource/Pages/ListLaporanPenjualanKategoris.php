<?php

namespace App\Filament\Owner\Resources\LaporanPenjualanKategoriResource\Pages;

use App\Filament\Owner\Resources\LaporanPenjualanKategoriResource;
use DB;
use Filament\Actions;
use Filament\Resources\Pages\Page;
use Filament\Resources\Pages\ListRecords;

class ListLaporanPenjualanKategoris extends Page
{
    protected static string $resource = LaporanPenjualanKategoriResource::class;
    protected static string $view = 'laporan.print-laporan-penjualan-kategori';
    protected function getHeaderActions(): array
    {
        return [
            Actions\CreateAction::make(),
        ];
    }

    public $data;
    public $tahun;

    public function mount(): void
    {
    // Ambil nilai tahun dari query string
    $this->tahun = request()->input('tahun') ?? now()->year;

    // Query berdasarkan filter tahun
    $this->data = DB::table('kategori_barang as k')
        ->leftJoin('barang as b', 'k.id_kategori', '=', 'b.id_kategori')
        ->select(
            'k.nama_kategori',
            DB::raw("COUNT(CASE WHEN b.status = 'Terjual' AND YEAR(b.tanggal_laku) = {$this->tahun} THEN 1 END) as terjual"),
            DB::raw("COUNT(CASE WHEN b.status IN ('Terdonasi', 'Hangus', 'Gagal', 'Batal') AND YEAR(b.tanggal_masuk) = {$this->tahun} THEN 1 END) as gagal")
        )
        ->groupBy('k.nama_kategori')
        ->get();
    }
}
