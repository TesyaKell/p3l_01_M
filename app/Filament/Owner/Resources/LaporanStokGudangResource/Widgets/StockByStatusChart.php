<?php

namespace App\Filament\Owner\Resources\LaporanStokGudangResource\Widgets;

use App\Models\Barang;
use Filament\Widgets\ChartWidget;
use Illuminate\Support\Facades\DB;

class StockByStatusChart extends ChartWidget
{
    protected static ?string $heading = 'Status Barang';
    protected static ?int $sort = 3;

    protected function getData(): array
    {
        $data = DB::table('barang')
            ->select('status', DB::raw('COUNT(*) as jumlah'))
            ->groupBy('status')
            ->get();

        $labels = [];
        $values = [];
        $backgroundColors = [
            'Tersedia' => '#10b981',    // Green
            'Terjual' => '#f59e0b',     // Yellow
            'Terdonasi' => '#ef4444',   // Red
            'Diambil' => '#2563eb',     // Blue
        ];

        $colors = [];

        foreach ($data as $item) {
            $labels[] = $item->status;
            $values[] = $item->jumlah;
            $colors[] = $backgroundColors[$item->status] ?? '#6b7280'; // Gray default
        }

        return [
            'datasets' => [
                [
                    'label' => 'Jumlah Barang',
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
