<?php

namespace App\Filament\Owner\Resources\LaporanPenjualanBulananKeseluruhanResource\Widgets;

use App\Models\DetailTransaksi;
use Filament\Widgets\ChartWidget;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Carbon;

class TopProductsChart extends ChartWidget
{
    protected static ?string $heading = 'Kategori Produk Terlaris';
    protected static ?int $sort = 3;

    public function getHeading(): string
    {
        $bulan = session('selected_month', date('m'));
        $tahun = session('selected_year', date('Y'));

        $bulanFormat = Carbon::createFromDate($tahun, $bulan, 1)->locale('id')->format('F Y');

        return 'Kategori Produk Terlaris - ' . $bulanFormat;
    }

    protected function getData(): array
    {
        $bulan = session('selected_month', date('m'));
        $tahun = session('selected_year', date('Y'));

        $data = DB::table('detail_transaksi')
            ->join('transaksi', 'detail_transaksi.no_nota', '=', 'transaksi.no_nota')
            ->join('barang', 'detail_transaksi.kode_barang', '=', 'barang.kode_barang')
            ->join('kategori_barang', 'barang.id_kategori', '=', 'kategori_barang.id_kategori')
            ->select('kategori_barang.nama_kategori', DB::raw('COUNT(*) as jumlah'))
            ->whereMonth('transaksi.tanggal_pesan', $bulan)
            ->whereYear('transaksi.tanggal_pesan', $tahun)
            ->where('transaksi.status', 'Selesai')
            ->groupBy('kategori_barang.nama_kategori')
            ->orderBy('jumlah', 'desc')
            ->limit(5)
            ->get();

        $labels = [];
        $values = [];
        $backgroundColors = [
            '#2563eb', // Blue
            '#10b981', // Green
            '#f59e0b', // Yellow
            '#8b5cf6', // Purple
            '#ef4444', // Red
        ];

        foreach ($data as $index => $item) {
            $labels[] = $item->nama_kategori;
            $values[] = $item->jumlah;
        }

        // If no data, show a message
        if (empty($labels)) {
            $labels = ['Tidak ada data'];
            $values = [1];
            $backgroundColors = ['#d1d5db']; // Gray
        }

        return [
            'datasets' => [
                [
                    'label' => 'Jumlah Terjual',
                    'data' => $values,
                    'backgroundColor' => array_slice($backgroundColors, 0, count($values)),
                ],
            ],
            'labels' => $labels,
        ];
    }

    protected function getType(): string
    {
        return 'pie';
    }
}
