<?php

namespace App\Filament\Owner\Resources\LaporanKomisiBulananperProdukResource\Pages;

use App\Filament\Owner\Resources\LaporanKomisiBulananperProdukResource;
use Filament\Actions;
use Filament\Resources\Pages\ListRecords;
use Carbon\Carbon;
use Filament\Forms\Components\Select;
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
        // Initialize with session values or current date
        $this->selectedMonth = session('selectedMonth', date('m')); // June 2025 as default (06)
        $this->selectedYear = session('selectedYear', date('Y'));  // 2025 as default
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
                         ->default($this->selectedMonth),
                            Select::make('year')
                                ->label('Tahun')
                                ->options(function () {
                                    $years = [];
                                    $currentYear = date('Y');
                                    for ($i = $currentYear; $i >= $currentYear - 5; $i--) {
                                        $years[$i] = $i;
                                    }
                                    return $years;
                                })
                                ->default($this->selectedYear),
                        ])

                 ->action(function (array $data): void {
                     $this->selectedMonth = $data['bulan'];
                     $this->selectedYear = $data['year'];

                     // Store in session
                     session(['selected_month' => $this->selectedMonth]);
                     session(['selected_year' => $this->selectedYear]);

                     // Apply table filters
                     $this->applyFilters();

                     // Show notification
                     $monthName = Carbon::createFromDate($this->selectedYear, $this->selectedMonth, 1)->format('F');
                     Notification::make()
                         ->title("Laporan bulan {$monthName} {$this->selectedYear} ditampilkan")
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

    protected function applyFilters(): void
    {
        $this->tableFilters['bulan']['value'] = $this->selectedMonth;
        $this->tableFilters['tahun']['value'] = $this->selectedYear;
    }
    protected function getHeaderWidgets(): array
    {
        return [
            LaporanKomisiBulananperProdukResource\Widgets\CommissionOverviewWidget::class,
        ];
    }

    public function getViewData(): array
    {
        $monthName = Carbon::createFromDate($this->selectedYear, $this->selectedMonth, 1)->locale('id')->format('F');

        return [
            'selectedMonthName' => $monthName,
            'selectedYear' => $this->selectedYear,
        ];
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

        $pdf = Pdf::loadView('laporan-komi si', $data);
        return response()->streamDownload(function () use ($pdf) {
            echo $pdf->output();
        }, 'laporan-komisi-bulanan-' . $tahun . '-' . $bulan . '.pdf');
    }

}
