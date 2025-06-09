<?php

namespace App\Filament\Owner\Resources\LaporanPenjualanBulananKeseluruhanResource\Pages;

use App\Filament\Owner\Resources\LaporanPenjualanBulananKeseluruhanResource;
use Filament\Actions;
use Filament\Resources\Pages\ListRecords;
use Filament\Forms\Components\Select;
use Filament\Forms\Components\Grid;
use Filament\Forms\Form;
use Filament\Notifications\Notification;
use Illuminate\Support\Carbon;
use Barryvdh\DomPDF\Facade\Pdf;
use App\Models\Transaksi;
use App\Models\DetailTransaksi;

class ListLaporanPenjualanBulananKeseluruhans extends ListRecords
{
    protected static string $resource = LaporanPenjualanBulananKeseluruhanResource::class;

    public $selectedMonth;
    public $selectedYear;

    public function mount(): void
    {
        parent::mount();

        // Default to current month and year if not set
        $this->selectedMonth = session('selected_month', date('m'));
        $this->selectedYear = session('selected_year', date('Y'));

        // Store in session
        session(['selected_month' => $this->selectedMonth]);
        session(['selected_year' => $this->selectedYear]);
    }

    protected function getHeaderActions(): array
    {
        return [
            Actions\Action::make('change_month')
                ->label('Pilih Bulan')
                ->icon('heroicon-o-calendar')
                ->form([
                    Grid::make(2)
                        ->schema([
                            Select::make('month')
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
                        ]),
                ])
                ->action(function (array $data): void {
                    $this->selectedMonth = $data['month'];
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

                    // Refresh the page to update widgets
                    $this->redirect(request()->header('Referer'));
                }),

            Actions\Action::make('export_monthly_report')

                    ->label('Export Laporan Tahunan')
                    ->icon('heroicon-o-document-arrow-down')
                    ->action(function () {
                        return self::generateYearlyReport();
                    }),
        ];
    }
    public static function generateYearlyReport($year = null)
    {
        $tahun = $year ?? session('selected_year', date('Y'));

        // Get monthly sales data
        $penjualanBulanan = [];
        $totalBarang = 0;
        $totalPenjualan = 0;

        for ($bulan = 1; $bulan <= 12; $bulan++) {
            $bulanStr = str_pad($bulan, 2, '0', STR_PAD_LEFT);

            // Get transactions for this month
            $transaksi = Transaksi::whereMonth('tanggal_pesan', $bulan)
                ->whereYear('tanggal_pesan', $tahun)
                ->where('status', 'Selesai')
                ->get();

            // Get total items sold
            $jumlahBarang = DetailTransaksi::whereIn('no_nota', $transaksi->pluck('no_nota'))
                ->count();

            // Get total sales
            $totalPenjualanBulan = $transaksi->sum('total_pembayaran');

            $penjualanBulanan[$bulanStr] = [
                'jumlah_barang' => $jumlahBarang,
                'total_penjualan' => $totalPenjualanBulan,
            ];

            $totalBarang += $jumlahBarang;
            $totalPenjualan += $totalPenjualanBulan;
        }

        $data = [
            'tahun' => $tahun,
            'tanggal_cetak' => now()->format('d/m/Y H:i:s'),
            'penjualan_bulanan' => $penjualanBulanan,
            'total_barang' => $totalBarang,
            'total_penjualan' => $totalPenjualan,
        ];

        // Use the simple template for better visualization
        $pdf = Pdf::loadView('laporanBulananKeseluruhan', $data);
        return response()->streamDownload(function () use ($pdf) {
            echo $pdf->output();
        }, 'laporan-tahunan-' . $tahun . '.pdf');
    }


    protected function applyFilters(): void
    {
        $this->tableFilters['bulan']['value'] = $this->selectedMonth;
        $this->tableFilters['tahun']['value'] = $this->selectedYear;
    }

    protected function getHeaderWidgets(): array
    {
        return [
            LaporanPenjualanBulananKeseluruhanResource\Widgets\SalesOverviewWidget::class,
            LaporanPenjualanBulananKeseluruhanResource\Widgets\MonthlySalesChart::class,
            LaporanPenjualanBulananKeseluruhanResource\Widgets\TopProductsChart::class,
            LaporanPenjualanBulananKeseluruhanResource\Widgets\SalesByStatusChart::class,
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
}
