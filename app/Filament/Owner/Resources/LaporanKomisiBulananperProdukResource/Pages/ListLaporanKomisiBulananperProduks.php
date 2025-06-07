<?php

namespace App\Filament\Owner\Resources\LaporanKomisiBulananperProdukResource\Pages;

use App\Filament\Owner\Resources\LaporanKomisiBulananperProdukResource;
use Filament\Actions;
use Filament\Resources\Pages\ListRecords;
use Carbon\Carbon;
use App\Models\DetailTransaksi;
use Barryvdh\DomPDF\Facade\Pdf;
use Illuminate\Support\Facades\DB;
use Filament\Forms;
use Filament\Notifications\Notification;

class ListLaporanKomisiBulananperProduks extends ListRecords
{
    protected static string $resource = LaporanKomisiBulananperProdukResource::class;

    // Add these properties to store the selected month and year
    public $selectedMonth;
    public $selectedYear;

    public function mount(): void
    {
        parent::mount();
        // Set default values to current month and year
        $this->selectedMonth = date('m');
        $this->selectedYear = date('Y');
    }

    protected function getHeaderActions(): array
    {
        return [
            Actions\Action::make('change_month')
                ->label('Pilih Bulan')
                ->icon('heroicon-o-calendar')
                ->form([
                    Forms\Components\Select::make('bulan')
                        ->label('Bulan')
                        ->options([
                            '01' => 'Januari',
                            '02' => 'Februari',
                            '03' => 'Maret',
                            '04' => 'April',
                            '05' => 'Mei',
                            '06' => 'Juni',
                            '07' => 'Juli',
                            '08' => 'Agustus',
                            '09' => 'September',
                            '10' => 'Oktober',
                            '11' => 'November',
                            '12' => 'Desember',
                        ])
                        ->default(fn () => $this->selectedMonth)
                        ->required(),
                    Forms\Components\Select::make('tahun')
                        ->label('Tahun')
                        ->options(function () {
                            $years = [];
                            $currentYear = date('Y');
                            for ($i = $currentYear; $i >= $currentYear - 5; $i--) {
                                $years[$i] = $i;
                            }
                            return $years;
                        })
                        ->default(fn () => $this->selectedYear)
                        ->required(),
                ])
                ->action(function (array $data): void {
                    $this->selectedMonth = $data['bulan'];
                    $this->selectedYear = $data['tahun'];
                    session(['selectedMonth' => $data['bulan'], 'selectedYear' => $data['tahun']]); // Store in session
                    $this->resetTable();
                    // Dispatch event to update the widget (adjust if needed)
                    $this->dispatch('updateWidgets')->to(\App\Filament\Owner\Resources\LaporanKomisiBulananperProdukResource\Widgets\CommissionOverviewWidget::class);

                    Notification::make()
                        ->title('Filter diterapkan')
                        ->body('Menampilkan data untuk ' . Carbon::createFromDate($data['tahun'], $data['bulan'], 1)->format('F Y'))
                        ->success()
                        ->send();
                }),
            Actions\Action::make('export_monthly_commission_report')
                ->label('Export Laporan Komisi Bulanan')
                ->icon('heroicon-o-document-arrow-down')
                ->action(function () {
                    return $this->generateMonthlyCommissionReport($this->selectedMonth, $this->selectedYear);
                })
                ->openUrlInNewTab(),
        ];
    }

    protected function getTableQuery(): \Illuminate\Database\Eloquent\Builder|null
    {
        return DetailTransaksi::query(); // Base query without filters, handled by table filters
    }

    protected function applyFilters(array $filters): void
    {
        $table = $this->getTable();
        foreach ($filters as $filterName => $filterValue) {
            $table->applyFilter($filterName, $filterValue);
        }
    }

    public function generateMonthlyCommissionReport($bulan = null, $tahun = null)
    {
        $bulan = $bulan ?? $this->selectedMonth ?? date('m');
        $tahun = $tahun ?? $this->selectedYear ?? date('Y');

        $detailTransaksi = DetailTransaksi::query()
            ->join('transaksi', 'detail_transaksi.no_nota', '=', 'transaksi.no_nota')
            ->join('barang', 'detail_transaksi.kode_barang', '=', 'barang.kode_barang')
            ->where('transaksi.status', 'Selesai')
            ->whereMonth('barang.tanggal_laku', $bulan)
            ->whereYear('barang.tanggal_laku', $tahun)
            ->select([
                'detail_transaksi.*',
                'barang.tanggal_masuk',
                'barang.tanggal_laku',
                'barang.nama_barang as barang_nama'
            ])
            ->get();

        $data = [
            'detail_transaksi' => $detailTransaksi,
            'tanggal_cetak' => now()->format('d/m/Y H:i:s'),
            'bulan' => Carbon::createFromDate($tahun, $bulan, 1)->format('F Y'),
            'total_komisi_reusmart' => $detailTransaksi->sum('komisi_reusmart'),
            'total_komisi_hunter' => $detailTransaksi->sum('komisi_hunter'),
            'total_komisi_penitip' => $detailTransaksi->sum('komisi_penitip'),
            'jumlah_produk' => $detailTransaksi->count(),
            'statistik_harian' => $this->getStatistikKomisiHarian($bulan, $tahun),
            'top_products' => $this->getTopCommissionProducts($bulan, $tahun),
        ];

        $pdf = Pdf::loadView('laporan-komisi', $data);
        return response()->streamDownload(function () use ($pdf) {
            echo $pdf->output();
        }, 'laporan-komisi-bulanan-' . $tahun . '-' . $bulan . '.pdf');
    }

    protected function getTopCommissionProducts($bulan, $tahun)
    {
        return DB::table('detail_transaksi')
            ->join('transaksi', 'detail_transaksi.no_nota', '=', 'transaksi.no_nota')
            ->join('barang', 'detail_transaksi.kode_barang', '=', 'barang.kode_barang')
            ->select(
                'detail_transaksi.kode_barang',
                'detail_transaksi.nama_barang',
                'detail_transaksi.komisi_reusmart',
                'detail_transaksi.komisi_hunter',
                'detail_transaksi.komisi_penitip',
                DB::raw('(detail_transaksi.komisi_reusmart + detail_transaksi.komisi_hunter + detail_transaksi.komisi_penitip) as total_komisi')
            )
            ->whereMonth('barang.tanggal_laku', $bulan)
            ->whereYear('barang.tanggal_laku', $tahun)
            ->where('transaksi.status', 'Selesai')
            ->orderBy('total_komisi', 'desc')
            ->limit(10)
            ->get();
    }

    protected function getStatistikKomisiHarian($bulan, $tahun)
    {
        return DB::table('detail_transaksi')
            ->join('transaksi', 'detail_transaksi.no_nota', '=', 'transaksi.no_nota')
            ->join('barang', 'detail_transaksi.kode_barang', '=', 'barang.kode_barang')
            ->select(
                DB::raw('DAY(barang.tanggal_laku) as hari'),
                DB::raw('SUM(detail_transaksi.komisi_reusmart) as total_komisi_reusmart'),
                DB::raw('SUM(detail_transaksi.komisi_hunter) as total_komisi_hunter'),
                DB::raw('SUM(detail_transaksi.komisi_penitip) as total_komisi_penitip'),
                DB::raw('COUNT(*) as jumlah_produk')
            )
            ->whereMonth('barang.tanggal_laku', $bulan)
            ->whereYear('barang.tanggal_laku', $tahun)
            ->where('transaksi.status', 'Selesai')
            ->groupBy(DB::raw('DAY(barang.tanggal_laku)'))
            ->orderBy('hari')
            ->get();
    }

    // protected function getHeaderWidgets(): array
    // {
    //     return [
    //         LaporanKomisiBulananperProdukResource\Widgets\CommissionOverviewWidget::class::make([
    //             'selectedMonth' => $this->selectedMonth,
    //             'selectedYear' => $this->selectedYear,
    //         ]),
    //     ];
    // }
}
