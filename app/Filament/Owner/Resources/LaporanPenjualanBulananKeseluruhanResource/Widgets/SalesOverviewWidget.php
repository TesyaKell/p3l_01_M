<?php

namespace App\Filament\Owner\Resources\LaporanPenjualanBulananKeseluruhanResource\Widgets;

use App\Models\Transaksi;
use App\Models\Pembeli;
use App\Models\DetailTransaksi;
use Filament\Widgets\StatsOverviewWidget as BaseWidget;
use Filament\Widgets\StatsOverviewWidget\Stat;
use Illuminate\Support\Carbon;

class SalesOverviewWidget extends BaseWidget
{
    protected function getStats(): array
    {
        $bulan = session('selected_month', date('m'));
        $tahun = session('selected_year', date('Y'));

        // Format bulan untuk tampilan
        $bulanFormat = Carbon::createFromDate($tahun, $bulan, 1)->locale('id')->format('F Y');

        // Transaksi bulan ini
        $transaksiSelesai = Transaksi::whereMonth('tanggal_pesan', $bulan)
            ->whereYear('tanggal_pesan', $tahun)
            ->where('status', 'Selesai')
            ->get();

        $totalPenjualan = $transaksiSelesai->sum('total_pembayaran');
        $jumlahTransaksi = $transaksiSelesai->count();

        // Transaksi bulan lalu untuk perbandingan
        $bulanLalu = Carbon::createFromDate($tahun, $bulan, 1)->subMonth();
        $transaksiSelesaiBulanLalu = Transaksi::whereMonth('tanggal_pesan', $bulanLalu->format('m'))
            ->whereYear('tanggal_pesan', $bulanLalu->format('Y'))
            ->where('status', 'Selesai')
            ->get();

        $totalPenjualanBulanLalu = $transaksiSelesaiBulanLalu->sum('total_pembayaran');
        $jumlahTransaksiBulanLalu = $transaksiSelesaiBulanLalu->count();

        // Hitung persentase perubahan
        $persentasePenjualan = $totalPenjualanBulanLalu > 0
            ? round((($totalPenjualan - $totalPenjualanBulanLalu) / $totalPenjualanBulanLalu) * 100, 2)
            : 100;

        $persentaseTransaksi = $jumlahTransaksiBulanLalu > 0
            ? round((($jumlahTransaksi - $jumlahTransaksiBulanLalu) / $jumlahTransaksiBulanLalu) * 100, 2)
            : 100;

        // Jumlah pelanggan unik bulan ini
        $jumlahPelanggan = Transaksi::whereMonth('tanggal_pesan', $bulan)
            ->whereYear('tanggal_pesan', $tahun)
            ->where('status', 'Selesai')
            ->distinct('id_pembeli')
            ->count('id_pembeli');

        // Rata-rata nilai order
        $rataRataOrder = $jumlahTransaksi > 0 ? $totalPenjualan / $jumlahTransaksi : 0;

        return [
            Stat::make('Total Penjualan ' . $bulanFormat, 'Rp ' . number_format($totalPenjualan, 0, ',', '.'))
                ->description($persentasePenjualan >= 0 ? $persentasePenjualan . '% kenaikan' : abs($persentasePenjualan) . '% penurunan')
                ->descriptionIcon($persentasePenjualan >= 0 ? 'heroicon-m-arrow-trending-up' : 'heroicon-m-arrow-trending-down')
                ->color($persentasePenjualan >= 0 ? 'success' : 'danger'),

            Stat::make('Jumlah Transaksi', $jumlahTransaksi)
                ->description($persentaseTransaksi >= 0 ? $persentaseTransaksi . '% kenaikan' : abs($persentaseTransaksi) . '% penurunan')
                ->descriptionIcon($persentaseTransaksi >= 0 ? 'heroicon-m-arrow-trending-up' : 'heroicon-m-arrow-trending-down')
                ->color($persentaseTransaksi >= 0 ? 'success' : 'danger'),

            Stat::make('Jumlah Pelanggan', $jumlahPelanggan)
                ->description('Pelanggan unik bulan ini')
                ->color('primary'),

            Stat::make('Rata-rata Nilai Order', 'Rp ' . number_format($rataRataOrder, 0, ',', '.'))
                ->description('Per transaksi')
                ->color('warning'),
        ];
    }
}
