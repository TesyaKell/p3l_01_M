<?php

namespace App\Filament\Owner\Resources;

use App\Filament\Owner\Resources\LaporanPenjualanBulananKeseluruhanResource\Pages;
use App\Filament\Owner\Resources\LaporanPenjualanBulananKeseluruhanResource\Widgets;
use App\Models\Transaksi;
use App\Models\DetailTransaksi;
use Filament\Forms;
use Filament\Forms\Form;
use Filament\Resources\Resource;
use Filament\Tables;
use Filament\Tables\Table;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\SoftDeletingScope;
use Filament\Tables\Filters\Filter;
use Filament\Tables\Filters\SelectFilter;
use Illuminate\Support\Carbon;
use Barryvdh\DomPDF\Facade\Pdf;
use Illuminate\Support\Facades\DB;
use Illuminate\Database\Eloquent\Collection;

class LaporanPenjualanBulananKeseluruhanResource extends Resource
{
    protected static ?string $model = Transaksi::class;
    protected static ?string $navigationIcon = 'heroicon-o-document-text';
    protected static ?string $navigationLabel = 'Laporan Penjualan Bulanan Keseluruhan';
    protected static ?string $navigationGroup = 'Laporan';
    protected static ?int $navigationSort = 1;

    public static function form(Form $form): Form
    {
        return $form
            ->schema([
                // Not needed for reporting
            ]);
    }

    public static function table(Table $table): Table
    {
        return $table
            ->columns([
                Tables\Columns\TextColumn::make('no_nota')
                    ->label('No. Nota')
                    ->searchable()
                    ->sortable(),
                Tables\Columns\TextColumn::make('tanggal_pesan')
                    ->label('Tanggal Transaksi')
                    ->date('d/m/Y')
                    ->sortable(),
                Tables\Columns\TextColumn::make('pembeli.nama_pembeli')
                    ->label('Nama Pembeli')
                    ->searchable(),
                Tables\Columns\TextColumn::make('detailTransaksi.nama_barang')
                    ->label('Produk')
                    ->listWithLineBreaks()
                    ->limitList(2)
                    ->expandableLimitedList(),
                Tables\Columns\TextColumn::make('total_pembayaran')
                    ->label('Total Pembayaran')
                    ->money('IDR')
                    ->sortable(),
                Tables\Columns\BadgeColumn::make('status')
                    ->label('Status')
                    ->colors([
                        'success' => 'Selesai',
                        'warning' => fn ($state) => in_array($state, ['menunggu pembayaran', 'Menunggu Konfirmasi']),
                        'danger' => 'Batal',
                        'primary' => fn ($state) => in_array($state, ['Disiapkan', 'Dikirim']),
                    ]),
            ])
            ->defaultSort('tanggal_pesan', 'desc')
            ->filters([
                Filter::make('created_at')
                    ->form([
                        Forms\Components\DatePicker::make('dari_tanggal')
                            ->label('Dari Tanggal'),
                        Forms\Components\DatePicker::make('sampai_tanggal')
                            ->label('Sampai Tanggal'),
                    ])
                    ->query(function (Builder $query, array $data): Builder {
                        return $query
                            ->when(
                                $data['dari_tanggal'],
                                fn (Builder $query, $date): Builder => $query->whereDate('tanggal_pesan', '>=', $date),
                            )
                            ->when(
                                $data['sampai_tanggal'],
                                fn (Builder $query, $date): Builder => $query->whereDate('tanggal_pesan', '<=', $date),
                            );
                    })
                    ->indicateUsing(function (array $data): array {
                        $indicators = [];

                        if ($data['dari_tanggal'] ?? null) {
                            $indicators['dari_tanggal'] = 'Dari tanggal: ' . Carbon::parse($data['dari_tanggal'])->format('d/m/Y');
                        }

                        if ($data['sampai_tanggal'] ?? null) {
                            $indicators['sampai_tanggal'] = 'Sampai tanggal: ' . Carbon::parse($data['sampai_tanggal'])->format('d/m/Y');
                        }

                        return $indicators;
                    }),
                SelectFilter::make('status')
                    ->options([
                        'menunggu pembayaran' => 'Menunggu Pembayaran',
                        'Menunggu Konfirmasi' => 'Menunggu Konfirmasi',
                        'Disiapkan' => 'Disiapkan',
                        'Dikirim' => 'Dikirim',
                        'Selesai' => 'Selesai',
                        'Batal' => 'Batal',
                    ]),
                SelectFilter::make('bulan')
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
                    ->default(session('selected_month', date('m')))
                    ->query(function (Builder $query, $state): Builder {
                        return $query->whereMonth('tanggal_pesan', $state);
                    }),
                SelectFilter::make('tahun')
                    ->label('Tahun')
                    ->options(function () {
                        $years = [];
                        $currentYear = date('Y');
                        for ($i = $currentYear; $i >= $currentYear - 5; $i--) {
                            $years[$i] = $i;
                        }
                        return $years;
                    })
                    ->default(session('selected_year', date('Y')))
                    ->query(function (Builder $query, $state): Builder {
                        return $query->whereYear('tanggal_pesan', $state);
                    }),
            ])
            ->actions([

            ])
            ->bulkActions([
                Tables\Actions\BulkActionGroup::make([
                    Tables\Actions\BulkAction::make('export_pdf')
                        ->label('Export PDF')
                        ->icon('heroicon-o-document-arrow-down')
                        ->action(function (Collection $records) {
                            return self::generateBulkPdf($records);
                        }),
                ]),
            ]);

    }

    public static function getRelations(): array
    {
        return [
            //
        ];
    }

    public static function getPages(): array
    {
        return [
            'index' => Pages\ListLaporanPenjualanBulananKeseluruhans::route('/'),
        ];
    }

    public static function getWidgets(): array
    {
        return [
            Widgets\SalesOverviewWidget::class,
            Widgets\MonthlySalesChart::class,
            Widgets\TopProductsChart::class,
            Widgets\SalesByStatusChart::class,
        ];
    }

    protected static function generateBulkPdf($records)
    {
        $data = [
            'transaksi' => $records,
            'tanggal_cetak' => now()->format('d/m/Y H:i:s'),
            'total_penjualan' => $records->sum('total_pembayaran'),
            'jumlah_transaksi' => $records->count(),
        ];

        $pdf = Pdf::loadView('laporan-penjualan', $data);
        return response()->streamDownload(function () use ($pdf) {
            echo $pdf->output();
        }, 'laporan-penjualan-' . now()->format('Y-m-d') . '.pdf');
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
        $pdf = Pdf::loadView('laporan-bulanan-keseluruhan', $data);
        return response()->streamDownload(function () use ($pdf) {
            echo $pdf->output();
        }, 'laporan-tahunan-' . $tahun . '.pdf');
    }

    public static function generateMonthlyReport($month = null, $year = null)
    {
        // Pastikan $tahun didefinisikan dengan nilai default jika tidak ada input
        $tahun = $year ?? request()->get('tableFilters.tahun', date('Y'));

        // Ambil data penjualan bulanan dari DB
        $penjualan_bulanan = DB::table('transaksi')
            ->select(
                DB::raw('MONTH(tanggal_pesan) as bulan'),
                DB::raw('COUNT(*) as jumlah_transaksi'),
                DB::raw('SUM(total_pembayaran) as total_penjualan')
            )
            ->whereYear('tanggal_pesan', $tahun)
            ->where('status', 'Selesai')
            ->groupBy(DB::raw('MONTH(tanggal_pesan)'))
            ->get()
            ->keyBy('bulan');

        // Format data untuk tabel dan grafik
        $formatted_data = [];
        $total_barang = 0;
        $total_penjualan = 0;
        $max_penjualan = 0;

        foreach (range(1, 12) as $month) {
            $data = $penjualan_bulanan[$month] ?? null;
            $formatted_data[$month] = [
                'jumlah_barang' => $data ? $data->jumlah_transaksi : '---',
                'total_penjualan' => $data ? $data->total_penjualan : 0,
            ];

            if ($data) {
                $total_barang += $data->jumlah_transaksi;
                $total_penjualan += $data->total_penjualan;
                $max_penjualan = max($max_penjualan, $data->total_penjualan);
            }
        }

        // Data untuk laporan
        $data = [
            'penjualan_bulanan' => $formatted_data,
            'total_barang' => $total_barang ?: '---',
            'total_penjualan' => $total_penjualan,
            'max_penjualan' => $max_penjualan,
            'tahun' => $tahun,
            'tanggal_cetak' => now()->format('d F Y'), // Sesuai format di gambar, misalnya "7 Juni 2025"
        ];

        // Generate PDF
        $pdf = Pdf::loadView('laporan-bulanan', $data);
        return response()->streamDownload(function () use ($pdf, $data) {
            echo $pdf->output();
        }, 'laporan-bulanan-' . $data['tahun'] . '.pdf');
    }

    protected static function getStatistikHarian($bulan, $tahun)
    {
        return DB::table('transaksi')
            ->select(DB::raw('DAY(tanggal_pesan) as hari'), DB::raw('SUM(total_pembayaran) as total'))
            ->whereMonth('tanggal_pesan', $bulan)
            ->whereYear('tanggal_pesan', $tahun)
            ->where('status', 'Selesai')
            ->groupBy(DB::raw('DAY(tanggal_pesan)'))
            ->orderBy('hari')
            ->get();
    }

    protected static function getProdukTerlaris($bulan, $tahun)
    {
        return DB::table('detail_transaksi')
            ->join('transaksi', 'detail_transaksi.no_nota', '=', 'transaksi.no_nota')
            ->join('barang', 'detail_transaksi.kode_barang', '=', 'barang.kode_barang')
            ->join('kategori_barang', 'barang.id_kategori', '=', 'kategori_barang.id_kategori')
            ->select('kategori_barang.nama_kategori', DB::raw('COUNT(*) as jumlah'), DB::raw('SUM(detail_transaksi.total) as total_penjualan'))
            ->whereMonth('transaksi.tanggal_pesan', $bulan)
            ->whereYear('transaksi.tanggal_pesan', $tahun)
            ->where('transaksi.status', 'Selesai')
            ->groupBy('kategori_barang.nama_kategori')
            ->orderBy('jumlah', 'desc')
            ->limit(5)
            ->get();
    }
}
