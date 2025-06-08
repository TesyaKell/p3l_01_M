<?php

namespace App\Filament\Owner\Resources\LaporanBarangWaktuTitipanHabisResource\Pages;

use App\Filament\Owner\Resources\LaporanBarangWaktuTitipanHabisResource;
use DB;
use Filament\Actions;
use Filament\Resources\Pages\ListRecords;
use Filament\Resources\Pages\Page;

class ListLaporanBarangWaktuTitipanHabis extends Page
{
    protected static string $resource = LaporanBarangWaktuTitipanHabisResource::class;
    protected static string $view = 'laporan.print-laporan-barang-waktu-titipan-habis';
    public $data;
    public $tanggalCetak;
    public $bulan;
    public $tahun;

    public function mount(): void
    {
        $this->bulan = request()->input('bulan') ?? now()->month;
        $this->tahun = request()->input('tahun') ?? now()->year;
        $this->tanggalCetak = now()->translatedFormat('d F Y');

        $this->data = DB::table('barang as b')
            ->join('penitip as p', 'b.id_penitip', '=', 'p.id_penitip')
            ->select(
                'b.kode_barang',
                'b.nama_barang',
                'b.id_penitip',
                'p.nama_penitip',
                'b.tanggal_masuk',
                'b.tanggal_akhir',
                'b.tanggal_batas'
            )
            ->whereMonth('b.tanggal_akhir', '=', $this->bulan)
            ->whereYear('b.tanggal_akhir', '=', $this->tahun)
            ->get();
    }



    protected function getHeaderActions(): array
    {
        return [
            Actions\CreateAction::make(),
        ];
    }
}
