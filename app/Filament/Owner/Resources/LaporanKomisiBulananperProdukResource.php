<?php

namespace App\Filament\Owner\Resources;

use App\Filament\Owner\Resources\LaporanKomisiBulananperProdukResource\Pages;
use App\Filament\Owner\Resources\LaporanKomisiBulananperProdukResource\Widgets;
use App\Models\DetailTransaksi;
use App\Models\Transaksi;
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

class LaporanKomisiBulananperProdukResource extends Resource
{
    protected static ?string $model = DetailTransaksi::class;
    protected static ?string $navigationIcon = 'heroicon-o-banknotes';
    protected static ?string $navigationLabel = 'Laporan Komisi Bulanan per Produk';
    protected static ?string $navigationGroup = 'Laporan';
    protected static ?int $navigationSort = 2;

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
            ->query(
                DetailTransaksi::query()
                    ->join('transaksi', 'detail_transaksi.no_nota', '=', 'transaksi.no_nota')
                    ->join('barang', 'detail_transaksi.kode_barang', '=', 'barang.kode_barang')
                    ->where('transaksi.status', 'Selesai')
                    ->select([
                        'detail_transaksi.*',
                        'barang.tanggal_masuk',
                        'barang.tanggal_laku',
                        'barang.nama_barang as barang_nama',
                        'transaksi.tanggal_pesan'
                    ])
            )
            ->columns([
                Tables\Columns\TextColumn::make('kode_barang')
                    ->label('Kode Barang')
                    ->searchable()
                    ->sortable(),
                Tables\Columns\TextColumn::make('nama_barang')
                    ->label('Nama Barang')
                    ->searchable()
                    ->getStateUsing(function ($record) {
                        return $record->barang_nama ?? $record->nama_barang;
                    }),
                Tables\Columns\TextColumn::make('harga_jual_bersih')
                    ->label('Harga Jual Bersih')
                    ->money('IDR')
                    ->sortable(),
                Tables\Columns\TextColumn::make('tanggal_masuk')
                    ->label('Tanggal Masuk')
                    ->date('d/m/Y')
                    ->sortable(),
                Tables\Columns\TextColumn::make('tanggal_laku')
                    ->label('Tanggal Laku')
                    ->date('d/m/Y')
                    ->sortable(),
                Tables\Columns\TextColumn::make('komisi_reusmart')
                    ->label('Komisi ReuSmart')
                    ->money('IDR')
                    ->sortable(),
                Tables\Columns\TextColumn::make('komisi_hunter')
                    ->label('Komisi Hunter')
                    ->money('IDR')
                    ->sortable(),
                Tables\Columns\TextColumn::make('komisi_penitip')
                    ->label('Komisi Penitip')
                    ->money('IDR')
                    ->sortable(),
                Tables\Columns\TextColumn::make('total_komisi')
                    ->label('Total Komisi')
                    ->money('IDR')
                    ->getStateUsing(function ($record) {
                        return ($record->komisi_reusmart ?? 0) + ($record->komisi_hunter ?? 0) + ($record->komisi_penitip ?? 0);
                    })
                    ->sortable(),
            ])
            ->defaultSort('tanggal_laku', 'desc')
            ->filters([
            Filter::make('tanggal_laku')
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
                                fn (Builder $query, $date): Builder => $query->whereDate('barang.tanggal_laku', '>=', $date),
                            )
                            ->when(
                                $data['sampai_tanggal'],
                                fn (Builder $query, $date): Builder => $query->whereDate('barang.tanggal_laku', '<=', $date),
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
            SelectFilter::make('bulan')
                    ->label('Bulan')
                    ->options([
                        '01' => 'Januari', '02' => 'Februari', '03' => 'Maret', '04' => 'April',
                        '05' => 'Mei', '06' => 'Juni', '07' => 'Juli', '08' => 'Agustus',
                        '09' => 'September', '10' => 'Oktober', '11' => 'November', '12' => 'Desember',
                    ])
                    ->default(session('selectedMonth', date('m'))) // Use session to sync with page
                    ->query(function (Builder $query, $state): Builder {
                        return $state ? $query->whereMonth('barang.tanggal_laku', $state) : $query;
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
                    ->default(session('selectedYear', date('Y'))) // Use session to sync with page
                    ->query(function (Builder $query, $state): Builder {
                        return $state ? $query->whereYear('barang.tanggal_laku', $state) : $query;
                    }),
            SelectFilter::make('kategori_komisi')
                    ->label('Kategori Komisi')
                    ->options([
                        'tinggi' => 'Komisi Tinggi (>500k)',
                        'sedang' => 'Komisi Sedang (100k-500k)',
                        'rendah' => 'Komisi Rendah (<100k)',
                    ])
                    ->query(function (Builder $query, $state): Builder {
                        return match ($state) {
                            'tinggi' => $query->whereRaw('(komisi_reusmart + komisi_hunter + komisi_penitip) > 500000'),
                            'sedang' => $query->whereRaw('(komisi_reusmart + komisi_hunter + komisi_penitip) BETWEEN 100000 AND 500000'),
                            'rendah' => $query->whereRaw('(komisi_reusmart + komisi_hunter + komisi_penitip) < 100000'),
                            default => $query,
                        };
                    }),
        ])
            ->actions([
            Tables\Actions\ViewAction::make(),
        ])
            ->bulkActions([])
            ->headerActions([]);
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
            'index' => Pages\ListLaporanKomisiBulananperProduks::route('/'),
        ];
    }

    public static function getWidgets(): array
    {
        return [
            Widgets\CommissionOverviewWidget::class,
        ];
    }

    protected static function generateBulkPdf($records)
    {
        $data = [
            'detail_transaksi' => $records,
            'tanggal_cetak' => now()->format('d/m/Y H:i:s'),
            'total_komisi_reusmart' => $records->sum('komisi_reusmart'),
            'total_komisi_hunter' => $records->sum('komisi_hunter'),
            'total_komisi_penitip' => $records->sum('komisi_penitip'),
            'jumlah_produk' => $records->count(),
        ];

        $pdf = Pdf::loadView('pdf.laporan-komisi', $data);
        return response()->streamDownload(function () use ($pdf) {
            echo $pdf->output();
        }, 'laporan-komisi-' . now()->format('Y-m-d') . '.pdf');
    }

    protected static function generateMonthlyCommissionReport($bulan = null, $tahun = null)
    {
        $page = request()->route()->controller; // Access the current page instance
        $bulan = $bulan ?? $page->selectedMonth ?? date('m');
        $tahun = $tahun ?? $page->selectedYear ?? date('Y');

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
            'statistik_harian' => self::getStatistikKomisiHarian($bulan, $tahun),
            'top_products' => self::getTopCommissionProducts($bulan, $tahun),
        ];

        $pdf = Pdf::loadView('laporan-komisi', $data);
        return response()->streamDownload(function () use ($pdf) {
            echo $pdf->output();
        }, 'laporan-komisi-bulanan-' . $tahun . '-' . $bulan . '.pdf');
    }

    protected static function getStatistikKomisiHarian($bulan, $tahun)
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

    protected static function getTopCommissionProducts($bulan, $tahun)
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
}
