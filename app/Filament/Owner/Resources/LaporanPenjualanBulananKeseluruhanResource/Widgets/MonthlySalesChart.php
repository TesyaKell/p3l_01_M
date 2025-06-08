<?php

namespace App\Filament\Owner\Resources\LaporanPenjualanBulananKeseluruhanResource\Widgets;

use App\Models\Transaksi;
use Filament\Widgets\ChartWidget;
use Illuminate\Support\Carbon;
use Illuminate\Support\Facades\DB;

class MonthlySalesChart extends ChartWidget
{
    protected static ?string $heading = 'Grafik Penjualan Harian';
    protected static ?int $sort = 2;

    public function getHeading(): string
    {
        $bulan = session('selected_month', date('m'));
        $tahun = session('selected_year', date('Y'));

        $bulanFormat = Carbon::createFromDate($tahun, $bulan, 1)->locale('id')->format('F Y');

        return 'Grafik Penjualan Harian - ' . $bulanFormat;
    }

    protected function getData(): array
    {
        $bulan = session('selected_month', date('m'));
        $tahun = session('selected_year', date('Y'));

        $data = DB::table('transaksi')
            ->select(DB::raw('DAY(tanggal_pesan) as hari'), DB::raw('SUM(total_pembayaran) as total'))
            ->whereMonth('tanggal_pesan', $bulan)
            ->whereYear('tanggal_pesan', $tahun)
            ->where('status', 'Selesai')
            ->groupBy(DB::raw('DAY(tanggal_pesan)'))
            ->orderBy('hari')
            ->get();

        $labels = [];
        $values = [];

        // Dapatkan jumlah hari dalam bulan
        $jumlahHari = Carbon::createFromDate($tahun, $bulan, 1)->daysInMonth;

        // Inisialisasi array dengan nilai 0 untuk semua hari
        for ($i = 1; $i <= $jumlahHari; $i++) {
            $labels[] = $i;
            $values[] = 0;
        }

        // Isi dengan data yang ada
        foreach ($data as $item) {
            $values[$item->hari - 1] = $item->total;
        }

        return [
            'datasets' => [
                [
                    'label' => 'Total Penjualan (Rp)',
                    'data' => $values,
                    'fill' => false,
                    'borderColor' => '#2563eb',
                    'tension' => 0.1,
                    'backgroundColor' => '#2563eb',
                ],
            ],
            'labels' => $labels,
        ];
    }

    protected function getType(): string
    {
        return 'line';
    }
}
