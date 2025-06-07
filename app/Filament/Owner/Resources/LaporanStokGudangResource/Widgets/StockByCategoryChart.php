<?php

namespace App\Filament\Owner\Resources\LaporanStokGudangResource\Widgets;

use App\Models\Barang;
use Filament\Widgets\ChartWidget;
use Illuminate\Support\Facades\DB;

class StockByCategoryChart extends ChartWidget
{
    protected static ?string $heading = 'Stok Berdasarkan Kategori';
    protected static ?int $sort = 2;

    protected function getData(): array
    {
        $data = DB::table('barang')
            ->join('kategori_barang', 'barang.id_kategori', '=', 'kategori_barang.id_kategori')
            ->select('kategori_barang.nama_kategori', DB::raw('COUNT(*) as jumlah'))
            ->groupBy('kategori_barang.nama_kategori')
            ->orderBy('jumlah', 'desc')
            ->limit(8)
            ->get();

        $labels = [];
        $values = [];
        $backgroundColors = [
            '#2563eb', '#10b981', '#f59e0b', '#8b5cf6',
            '#ef4444', '#06b6d4', '#84cc16', '#f97316'
        ];

        foreach ($data as $index => $item) {
            $labels[] = $item->nama_kategori;
            $values[] = $item->jumlah;
        }

        return [
            'datasets' => [
                [
                    'label' => 'Jumlah Barang',
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
