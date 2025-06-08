<?php

namespace App\Filament\Owner\Resources\LaporanKomisiBulananperProdukResource\Widgets;

use Filament\Widgets\StatsOverviewWidget as BaseWidget;
use Filament\Widgets\StatsOverviewWidget\Stat;
use Illuminate\Support\Carbon;
use Illuminate\Support\Facades\DB;

class CommissionOverviewWidget extends BaseWidget
{
    // Properties to store selected month and year, passed from the page
    public $selectedMonth;
    public $selectedYear;

    protected function getStats(): array
    {
        try {
            // Use the selected month and year from the page, with fallback to current date
            $bulan = session('selected_month', date('m'));
            $tahun = session('selected_year', date('Y'));

            // Format bulan untuk tampilan
            $bulanLabel = Carbon::createFromDate($tahun, $bulan, 1)->format('F Y');

            // Komisi bulan ini
            $komisiData = DB::table('detail_transaksi')
                ->join('transaksi', 'detail_transaksi.no_nota', '=', 'transaksi.no_nota')
                ->join('barang', 'detail_transaksi.kode_barang', '=', 'barang.kode_barang')
                ->where('transaksi.status', 'Selesai')
                ->whereMonth('barang.tanggal_laku', $bulan)
                ->whereYear('barang.tanggal_laku', $tahun)
                ->selectRaw('
                    SUM(detail_transaksi.komisi_reusmart) as total_komisi_reusmart,
                    SUM(detail_transaksi.komisi_hunter) as total_komisi_hunter,
                    SUM(detail_transaksi.komisi_penitip) as total_komisi_penitip,
                    COUNT(*) as jumlah_produk
                ')
                ->first();

            // Handle case where query returns null
            $komisiData = $komisiData ?: (object) ['total_komisi_reusmart' => 0, 'total_komisi_hunter' => 0, 'total_komisi_penitip' => 0, 'jumlah_produk' => 0];

            // Komisi bulan lalu untuk perbandingan
            $bulanLalu = Carbon::createFromDate($tahun, $bulan, 1)->subMonth();
            $komisiDataBulanLalu = DB::table('detail_transaksi')
                ->join('transaksi', 'detail_transaksi.no_nota', '=', 'transaksi.no_nota')
                ->join('barang', 'detail_transaksi.kode_barang', '=', 'barang.kode_barang')
                ->where('transaksi.status', 'Selesai')
                ->whereMonth('barang.tanggal_laku', $bulanLalu->format('m'))
                ->whereYear('barang.tanggal_laku', $bulanLalu->format('Y'))
                ->selectRaw('
                    SUM(detail_transaksi.komisi_reusmart) as total_komisi_reusmart,
                    SUM(detail_transaksi.komisi_hunter) as total_komisi_hunter,
                    SUM(detail_transaksi.komisi_penitip) as total_komisi_penitip,
                    COUNT(*) as jumlah_produk
                ')
                ->first();

            // Handle case where query returns null
            $komisiDataBulanLalu = $komisiDataBulanLalu ?: (object) ['total_komisi_reusmart' => 0, 'total_komisi_hunter' => 0, 'total_komisi_penitip' => 0, 'jumlah_produk' => 0];

            $totalKomisi = ($komisiData->total_komisi_reusmart ?? 0) +
                          ($komisiData->total_komisi_hunter ?? 0) +
                          ($komisiData->total_komisi_penitip ?? 0);

            $totalKomisiBulanLalu = ($komisiDataBulanLalu->total_komisi_reusmart ?? 0) +
                                   ($komisiDataBulanLalu->total_komisi_hunter ?? 0) +
                                   ($komisiDataBulanLalu->total_komisi_penitip ?? 0);

            // Hitung persentase perubahan
            $persentaseKomisi = $totalKomisiBulanLalu > 0
                ? round((($totalKomisi - $totalKomisiBulanLalu) / $totalKomisiBulanLalu) * 100, 2)
                : 100;

            return [
                Stat::make('Total Komisi ReuSmart', 'Rp ' . number_format($komisiData->total_komisi_reusmart ?? 0, 0, ',', '.'))
                    ->description('Periode: ' . $bulanLabel)
                    ->color('primary'),
                Stat::make('Total Komisi Hunter', 'Rp ' . number_format($komisiData->total_komisi_hunter ?? 0, 0, ',', '.'))
                    ->description('Periode: ' . $bulanLabel)
                    ->color('success'),
                Stat::make('Total Komisi Penitip', 'Rp ' . number_format($komisiData->total_komisi_penitip ?? 0, 0, ',', '.'))
                    ->description('Periode: ' . $bulanLabel)
                    ->color('warning'),
                Stat::make('Total Semua Komisi', 'Rp ' . number_format($totalKomisi, 0, ',', '.'))
                    ->description($persentaseKomisi >= 0 ? $persentaseKomisi . '% kenaikan' : abs($persentaseKomisi) . '% penurunan')
                    ->descriptionIcon($persentaseKomisi >= 0 ? 'heroicon-m-arrow-trending-up' : 'heroicon-m-arrow-trending-down')
                    ->color($persentaseKomisi >= 0 ? 'success' : 'danger'),
            ];
        } catch (\Exception $e) {
            // Log the error and return default stats
            \Log::error('Error in CommissionOverviewWidget::getStats: ' . $e->getMessage());
            $bulanLabel = Carbon::createFromDate(date('Y'), date('m'), 1)->format('F Y'); // June 2025
            return [
                Stat::make('Total Komisi ReuSmart', 'Rp 0')
                    ->description('Periode: ' . $bulanLabel)
                    ->color('primary'),
                Stat::make('Total Komisi Hunter', 'Rp 0')
                    ->description('Periode: ' . $bulanLabel)
                    ->color('success'),
                Stat::make('Total Komisi Penitip', 'Rp 0')
                    ->description('Periode: ' . $bulanLabel)
                    ->color('warning'),
                Stat::make('Total Semua Komisi', 'Rp 0')
                    ->description('0% kenaikan')
                    ->descriptionIcon('heroicon-m-arrow-trending-up')
                    ->color('success'),
            ];
        }
    }
}
