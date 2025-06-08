<?php

namespace App\Filament\Owner\Resources\LaporanPenjualanBulananKeseluruhanResource\Widgets;

use App\Models\Transaksi;
use Filament\Widgets\ChartWidget;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Carbon;

class SalesByStatusChart extends ChartWidget
{
    protected static ?string $heading = 'Transaksi Berdasarkan Status';
    protected static ?int $sort = 4;

    public function getHeading(): string
    {
        $bulan = session('selected_month', date('m'));
        $tahun = session('selected_year', date('Y'));

        $bulanFormat = Carbon::createFromDate($tahun, $bulan, 1)->locale('id')->format('F Y');

        return 'Transaksi Berdasarkan Status - ' . $bulanFormat;
    }

    protected function getData(): array
    {
        $bulan = session('selected_month', date('m'));
        $tahun = session('selected_year', date('Y'));

        $data = DB::table('transaksi')
            ->select('status', DB::raw('COUNT(*) as jumlah'))
            ->whereMonth('tanggal_pesan', $bulan)
            ->whereYear('tanggal_pesan', $tahun)
            ->groupBy('status')
            ->get();

        $labels = [];
        $values = [];
        $backgroundColors = [
            'Selesai' => '#10b981', // Green
            'Dikirim' => '#2563eb', // Blue
            'Disiapkan' => '#8b5cf6', // Purple
            'Menunggu Konfirmasi' => '#f59e0b', // Yellow
            'menunggu pembayaran' => '#f97316', // Orange
            'Batal' => '#ef4444', // Red
        ];

        $colors = [];

        foreach ($data as $item) {
            $labels[] = $item->status;
            $values[] = $item->jumlah;
            $colors[] = $backgroundColors[$item->status] ?? '#6b7280'; // Gray default
        }

        // If no data, show a message
        if (empty($labels)) {
            $labels = ['Tidak ada data'];
            $values = [1];
            $colors = ['#d1d5db']; // Gray
        }

        return [
            'datasets' => [
                [
                    'label' => 'Jumlah Transaksi',
                    'data' => $values,
                    'backgroundColor' => $colors,
                ],
            ],
            'labels' => $labels,
        ];
    }

    protected function getType(): string
    {
        return 'doughnut';
    }
}
