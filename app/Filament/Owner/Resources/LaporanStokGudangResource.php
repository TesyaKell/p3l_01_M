<?php

namespace App\Filament\Owner\Resources;

use App\Filament\Owner\Resources\LaporanStokGudangResource\Pages;
use App\Filament\Owner\Resources\LaporanStokGudangResource\Widgets;
use App\Models\Barang;
use App\Models\KategoriBarang;
use App\Models\Penitip;
use App\Models\Pegawai;
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
use Illuminate\Support\Collection;
use Filament\Notifications\Notification;

class LaporanStokGudangResource extends Resource
{
    protected static ?string $model = Barang::class;
    protected static ?string $navigationIcon = 'heroicon-o-archive-box';
    protected static ?string $navigationLabel = 'Laporan Stok Gudang';
    protected static ?string $navigationGroup = 'Laporan';
    protected static ?int $navigationSort = 2;
    protected static ?string $pluralModelLabel = 'Laporan Stok Gudang Hari Ini';

    public static function form(Form $form): Form
    {
        return $form
            ->schema([
                // Not needed for reporting
            ]);
    }

    public static function getEloquentQuery(): Builder
    {
        return parent::getEloquentQuery()
            ->where('status', 'Tersedia')
            ->with(['penitip', 'hunter', 'kategori']);
    }

    public static function table(Table $table): Table
    {
        return $table
            ->heading('Stok Gudang Per Hari Ini - ' . now()->format('d/m/Y'))
            ->description('Menampilkan stok yang tersedia di gudang pada hari ini')
            ->columns([
                Tables\Columns\TextColumn::make('kode_barang')
                    ->label('Kode Produk')
                    ->searchable()
                    ->sortable()
                    ->copyable()
                    ->copyMessage('Kode produk disalin!')
                    ->weight('bold'),

                Tables\Columns\TextColumn::make('nama_barang')
                    ->label('Nama Produk')
                    ->searchable()
                    ->sortable()
                    ->wrap()
                    ->limit(30),

                Tables\Columns\TextColumn::make('id_penitip')
                    ->label('ID Penitip')
                    ->searchable()
                    ->sortable(),

                Tables\Columns\TextColumn::make('penitip.nama_penitip')
                    ->label('Nama Penitip')
                    ->searchable()
                    ->sortable()
                    ->wrap(),

                Tables\Columns\TextColumn::make('tanggal_masuk')
                    ->label('Tanggal Masuk')
                    ->date('d/m/Y')
                    ->sortable()
                    ->color('primary'),
                // Tables\Columns\TextColumn::make('opsi')
                //     ->label('Perpanjangan')
                //     ->badge()
                //     ->colors([
                //         'success' => fn ($state) => trim(strtolower($state)) === 'diperpanjang',
                //         'warning' => fn ($state) => $state !== null && trim(strtolower($state)) !== 'diperpanjang' && !empty($state),
                //         'secondary' => fn ($state) => empty($state),
                //     ])
                //     ->formatStateUsing(fn ($state) => $state ?? 'Belum Ada Data'),


                Tables\Columns\TextColumn::make('id_hunter_pegawai')
                    ->label('ID Hunter')
                    ->searchable()
                    ->sortable()
                    ->placeholder('Tidak ada'),

                Tables\Columns\TextColumn::make('hunter.nama_pegawai')
                    ->label('Nama Hunter')
                    ->searchable()
                    ->sortable()
                    ->placeholder('Tidak ada hunter')
                    ->wrap(),

                Tables\Columns\TextColumn::make('harga')
                    ->label('Harga')
                    ->money('IDR')
                    ->sortable()
                    ->weight('bold')
                    ->color('success'),

                Tables\Columns\TextColumn::make('kategori.nama_kategori')
                    ->label('Kategori')
                    ->searchable()
                    ->sortable()
                    ->badge()
                    ->color('info'),

                Tables\Columns\TextColumn::make('tanggal_batas')
                    ->label('Batas Titip')
                    ->date('d/m/Y')
                    ->sortable()
                    ->color(function (Barang $record) {
                        if (!$record->tanggal_batas) {
                            return 'gray';
                        }
                        $batas = Carbon::parse($record->tanggal_batas);
                        $today = now();

                        if ($batas->isPast()) {
                            return 'danger';
                        }
                        if ($batas->diffInDays($today) <= 7) {
                            return 'warning';
                        }
                        return 'success';
                    })
                    ->tooltip(function (Barang $record) {
                        if (!$record->tanggal_batas) {
                            return 'Tidak ada batas';
                        }
                        $batas = Carbon::parse($record->tanggal_batas);
                        $today = now();

                        if ($batas->isPast()) {
                            return 'Sudah melewati batas ' . $batas->diffInDays($today) . ' hari';
                        }
                        return 'Sisa ' . $today->diffInDays($batas) . ' hari lagi';
                    }),
            ])
            ->defaultSort('tanggal_masuk', 'desc')
            ->filters([
                SelectFilter::make('opsi')
                    ->label('Status Perpanjangan')
                    ->options([
                        'Diperpanjang' => 'Diperpanjang',
                        'Belum Diperpanjang' => 'Belum Diperpanjang',
                    ])
                    ->query(function (Builder $query, $state): Builder {
                        if ($state['value'] === 'Diperpanjang') {
                            return $query->where('opsi', 'Diperpanjang');
                        } elseif ($state['value'] === 'Belum Diperpanjang') {
                            return $query->where(function ($q) {
                                $q->where('opsi', '!=', 'Diperpanjang')
                                  ->orWhereNull('opsi');
                            });
                        }
                        return $query;
                    }),

                SelectFilter::make('id_kategori')
                    ->label('Kategori')
                    ->relationship('kategori', 'nama_kategori')
                    ->searchable()
                    ->preload(),

                SelectFilter::make('id_penitip')
                    ->label('Penitip')
                    ->relationship('penitip', 'nama_penitip')
                    ->searchable()
                    ->preload(),

                SelectFilter::make('id_hunter_pegawai')
                    ->label('Hunter')
                    ->relationship('hunter', 'nama_pegawai')
                    ->searchable()
                    ->preload(),

                Filter::make('harga')
                    ->form([
                        Forms\Components\TextInput::make('harga_min')
                            ->label('Harga Minimum')
                            ->numeric()
                            ->prefix('Rp'),
                        Forms\Components\TextInput::make('harga_max')
                            ->label('Harga Maximum')
                            ->numeric()
                            ->prefix('Rp'),
                    ])
                    ->query(function (Builder $query, array $data): Builder {
                        return $query
                            ->when(
                                $data['harga_min'],
                                fn (Builder $query, $price): Builder => $query->where('harga', '>=', $price),
                            )
                            ->when(
                                $data['harga_max'],
                                fn (Builder $query, $price): Builder => $query->where('harga', '<=', $price),
                            );
                    })
                    ->indicateUsing(function (array $data): array {
                        $indicators = [];

                        if ($data['harga_min'] ?? null) {
                            $indicators['harga_min'] = 'Harga min: Rp ' . number_format($data['harga_min'], 0, ',', '.');
                        }

                        if ($data['harga_max'] ?? null) {
                            $indicators['harga_max'] = 'Harga max: Rp ' . number_format($data['harga_max'], 0, ',', '.');
                        }

                        return $indicators;
                    }),

                Filter::make('hari_di_gudang')
                    ->label('Lama di Gudang')
                    ->form([
                        Forms\Components\Select::make('periode')
                            ->label('Periode')
                            ->options([
                                'baru' => 'Baru (< 30 hari)',
                                'sedang' => 'Sedang (30-60 hari)',
                                'lama' => 'Lama (> 60 hari)',
                            ])
                    ])
                    ->query(function (Builder $query, array $data): Builder {
                        if (!isset($data['periode'])) {
                            return $query;
                        }

                        $today = now();

                        return match($data['periode']) {
                            'baru' => $query->where('tanggal_masuk', '>=', $today->copy()->subDays(30)),
                            'sedang' => $query->whereBetween('tanggal_masuk', [
                                $today->copy()->subDays(60),
                                $today->copy()->subDays(30)
                            ]),
                            'lama' => $query->where('tanggal_masuk', '<', $today->copy()->subDays(60)),
                            default => $query
                        };
                    })
                    ->indicateUsing(function (array $data): array {
                        if (!isset($data['periode'])) {
                            return [];
                        }

                        $labels = [
                            'baru' => 'Barang baru (< 30 hari)',
                            'sedang' => 'Barang sedang (30-60 hari)',
                            'lama' => 'Barang lama (> 60 hari)',
                        ];

                        return ['periode' => $labels[$data['periode']] ?? ''];
                    }),

                Filter::make('mendekati_batas')
                    ->label('Status Batas Titip')
                    ->form([
                        Forms\Components\Select::make('status_batas')
                            ->label('Status')
                            ->options([
                                'kritis' => 'Kritis (< 7 hari)',
                                'peringatan' => 'Peringatan (7-30 hari)',
                                'aman' => 'Aman (> 30 hari)',
                                'lewat' => 'Sudah Lewat Batas',
                            ])
                    ])
                    ->query(function (Builder $query, array $data): Builder {
                        if (!isset($data['status_batas'])) {
                            return $query;
                        }

                        $today = now();

                        return match($data['status_batas']) {
                            'kritis' => $query->whereBetween('tanggal_batas', [$today, $today->copy()->addDays(7)]),
                            'peringatan' => $query->whereBetween('tanggal_batas', [$today->copy()->addDays(7), $today->copy()->addDays(30)]),
                            'aman' => $query->where('tanggal_batas', '>', $today->copy()->addDays(30)),
                            'lewat' => $query->where('tanggal_batas', '<', $today),
                            default => $query
                        };
                    })
                    ->indicateUsing(function (array $data): array {
                        if (!isset($data['status_batas'])) {
                            return [];
                        }

                        $labels = [
                            'kritis' => 'Batas kritis (< 7 hari)',
                            'peringatan' => 'Batas peringatan (7-30 hari)',
                            'aman' => 'Batas aman (> 30 hari)',
                            'lewat' => 'Sudah lewat batas',
                        ];

                        return ['status_batas' => $labels[$data['status_batas']] ?? ''];
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
                            return static::generateBulkPdf($records);
                        }),

                    Tables\Actions\BulkAction::make('export_excel')
                        ->label('Export Excel')
                        ->icon('heroicon-o-table-cells')
                        ->action(function (Collection $records) {
                            return static::generateExcel($records);
                        }),
                ]),
            ])
            ->headerActions([
                Tables\Actions\Action::make('export_daily_report')
                    ->label('Export Laporan Harian')
                    ->icon('heroicon-o-document-arrow-down')
                    ->color('primary')
                    ->action(function () {
                        try {
                            return static::generateDailyReport();
                        } catch (\Exception $e) {
                            Notification::make()
                                ->title('Error')
                                ->body('Gagal menggenerate laporan: ' . $e->getMessage())
                                ->danger()
                                ->send();

                            return null;
                        }
                    }),
            ])
            ->emptyStateHeading('Tidak ada stok tersedia hari ini')
            ->emptyStateDescription('Tidak ada barang yang tersedia di gudang pada hari ini.')
            ->emptyStateIcon('heroicon-o-archive-box');
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
            'index' => Pages\ListLaporanStokGudangs::route('/'),
        ];
    }

    public static function getWidgets(): array
    {
        return [
            Widgets\CurrentStockOverviewWidget::class,
            Widgets\StockByCategoryChart::class,
        ];
    }

    public static function generateBulkPdf(Collection $records)
    {
        try {
            $data = [
                'barang' => $records->load(['penitip', 'hunter', 'kategori']),
                'tanggal_cetak' => now()->format('d/m/Y H:i:s'),
                'tanggal_laporan' => now()->format('d/m/Y'),
                'total_items' => $records->count(),
                'total_value' => $records->sum('harga'),
                'statistik' => static::getCurrentStockStatistik($records),
            ];

            $pdf = Pdf::loadView('pdf.laporan-stok-harian', $data)
                ->setPaper('a4', 'landscape')
                ->setOptions([
                    'defaultFont' => 'sans-serif',
                    'isRemoteEnabled' => true,
                    'isHtml5ParserEnabled' => true,
                ]);

            return response()->streamDownload(function () use ($pdf) {
                echo $pdf->output();
            }, 'laporan-stok-harian-' . now()->format('Y-m-d') . '.pdf');

        } catch (\Exception $e) {
            \Log::error('Error generating bulk PDF: ' . $e->getMessage());

            Notification::make()
                ->title('Error')
                ->body('Gagal menggenerate PDF: ' . $e->getMessage())
                ->danger()
                ->send();

            return null;
        }
    }

    public static function generateDailyReport()
    {
        try {
            $barang = Barang::with(['penitip', 'hunter', 'kategori'])
                ->where('status', 'Tersedia')
                ->get();

            if ($barang->isEmpty()) {
                Notification::make()
                    ->title('Peringatan')
                    ->body('Tidak ada stok tersedia untuk diekspor.')
                    ->warning()
                    ->send();

                return null;
            }

            $data = [
                'barang' => $barang,
                'tanggal_cetak' => now()->format('d/m/Y H:i:s'),
                'tanggal_laporan' => now()->format('d/m/Y'),
                'total_items' => $barang->count(),
                'total_value' => $barang->sum('harga'),
                'statistik' => static::getCurrentStockStatistik($barang),
                'statistik_kategori' => static::getCurrentStockByCategory(),
                'statistik_penitip' => static::getCurrentStockByPenitip(),
                'barang_kritis' => static::getBarangMendekatiBatas(),
            ];

            $pdf = Pdf::loadView('pdf.laporan-stok-harian-lengkap', $data)
                ->setPaper('a4', 'portrait')
                ->setOptions([
                    'defaultFont' => 'sans-serif',
                    'isRemoteEnabled' => true,
                    'isHtml5ParserEnabled' => true,
                ]);

            return response()->streamDownload(function () use ($pdf) {
                echo $pdf->output();
            }, 'laporan-stok-harian-lengkap-' . now()->format('Y-m-d') . '.pdf');

        } catch (\Exception $e) {
            \Log::error('Error generating daily report: ' . $e->getMessage());
            \Log::error('Stack trace: ' . $e->getTraceAsString());

            Notification::make()
                ->title('Error')
                ->body('Gagal menggenerate laporan harian: ' . $e->getMessage())
                ->danger()
                ->send();

            return null;
        }
    }

    public static function generateGudangReport()
    {
        try {
            $barang = Barang::with(['penitip', 'hunter', 'kategori'])
                ->where('status', 'Tersedia')
                ->get();

            if ($barang->isEmpty()) {
                Notification::make()
                    ->title('Peringatan')
                    ->body('Tidak ada stok tersedia untuk diekspor.')
                    ->warning()
                    ->send();

                return null;
            }

            $data = [
                'barang' => $barang,
                'tanggal_cetak' => now()->format('d/m/Y H:i:s'),
                'tanggal_laporan' => now()->format('d/m/Y'),
                'total_items' => $barang->count(),
                'total_value' => $barang->sum('harga'),
                'statistik' => static::getCurrentStockStatistik($barang),
            ];

            $pdf = Pdf::loadView('pdf.laporan-stok-gudang', $data)
                ->setPaper('a4', 'landscape')
                ->setOptions([
                    'defaultFont' => 'sans-serif',
                    'isRemoteEnabled' => true,
                    'isHtml5ParserEnabled' => true,
                ]);

            return response()->streamDownload(function () use ($pdf) {
                echo $pdf->output();
            }, 'laporan-stok-gudang-' . now()->format('Y-m-d') . '.pdf');

        } catch (\Exception $e) {
            \Log::error('Error generating gudang report: ' . $e->getMessage());
            \Log::error('Stack trace: ' . $e->getTraceAsString());

            Notification::make()
                ->title('Error')
                ->body('Gagal menggenerate laporan stok gudang: ' . $e->getMessage())
                ->danger()
                ->send();

            return null;
        }
    }

    public static function generateHarianReport()
    {
        try {
            $barang = Barang::with(['penitip', 'hunter', 'kategori'])
                ->where('status', 'Tersedia')
                ->whereDate('tanggal_masuk', now()->toDateString())
                ->get();

            if ($barang->isEmpty()) {
                Notification::make()
                    ->title('Peringatan')
                    ->body('Tidak ada stok masuk hari ini untuk diekspor.')
                    ->warning()
                    ->send();

                return null;
            }

            $data = [
                'barang' => $barang,
                'tanggal_cetak' => now()->format('d/m/Y H:i:s'),
                'tanggal_laporan' => now()->format('d/m/Y'),
                'total_items' => $barang->count(),
                'total_value' => $barang->sum('harga'),
                'statistik' => static::getCurrentStockStatistik($barang),
            ];

            $pdf = Pdf::loadView('pdf.laporan-stok-harian', $data)
                ->setPaper('a4', 'landscape')
                ->setOptions([
                    'defaultFont' => 'sans-serif',
                    'isRemoteEnabled' => true,
                    'isHtml5ParserEnabled' => true,
                ]);

            return response()->streamDownload(function () use ($pdf) {
                echo $pdf->output();
            }, 'laporan-stok-harian-' . now()->format('Y-m-d') . '.pdf');

        } catch (\Exception $e) {
            \Log::error('Error generating harian report: ' . $e->getMessage());
            \Log::error('Stack trace: ' . $e->getTraceAsString());

            Notification::make()
                ->title('Error')
                ->body('Gagal menggenerate laporan harian: ' . $e->getMessage())
                ->danger()
                ->send();

            return null;
        }
    }
    public static function generateExcel(Collection $records)
    {
        try {
            $headers = [
                'Content-Type' => 'text/csv',
                'Content-Disposition' => 'attachment; filename="stok-harian-' . now()->format('Y-m-d') . '.csv"',
            ];

            $callback = function () use ($records) {
                $file = fopen('php://output', 'w');

                fprintf($file, chr(0xEF).chr(0xBB).chr(0xBF));

                fputcsv($file, [
                    'Kode Produk',
                    'Nama Produk',
                    'ID Penitip',
                    'Nama Penitip',
                    'Tanggal Masuk',
                    'Hari di Gudang',
                    'Perpanjangan',
                    'ID Hunter',
                    'Nama Hunter',
                    'Harga',
                    'Kategori',
                    'Batas Titip',
                    'Status Batas'
                ]);

                foreach ($records as $barang) {
                    $hariDiGudang = $barang->tanggal_masuk ? Carbon::parse($barang->tanggal_masuk)->diffInDays(now()) : 0;
                    $statusBatas = 'N/A';

                    if ($barang->tanggal_batas) {
                        $batas = Carbon::parse($barang->tanggal_batas);
                        $today = now();

                        if ($batas->isPast()) {
                            $statusBatas = 'Sudah Lewat';
                        } elseif ($batas->diffInDays($today) <= 7) {
                            $statusBatas = 'Kritis';
                        } elseif ($batas->diffInDays($today) <= 30) {
                            $statusBatas = 'Peringatan';
                        } else {
                            $statusBatas = 'Aman';
                        }
                    }

                    fputcsv($file, [
                        $barang->kode_barang,
                        $barang->nama_barang,
                        $barang->id_penitip,
                        $barang->penitip->nama_penitip ?? '',
                        $barang->tanggal_masuk ? Carbon::parse($barang->tanggal_masuk)->format('d/m/Y') : '',
                        $hariDiGudang . ' hari',
                        $barang->opsi === 'Diperpanjang' ? 'Diperpanjang' : 'Belum Diperpanjang',
                        $barang->id_hunter_pegawai ?: '',
                        $barang->hunter->nama_pegawai ?? '',
                        $barang->harga,
                        $barang->kategori->nama_kategori ?? '',
                        $barang->tanggal_batas ? Carbon::parse($barang->tanggal_batas)->format('d/m/Y') : '',
                        $statusBatas
                    ]);
                }

                fclose($file);
            };

            return response()->stream($callback, 200, $headers);

        } catch (\Exception $e) {
            \Log::error('Error generating Excel: ' . $e->getMessage());

            Notification::make()
                ->title('Error')
                ->body('Gagal menggenerate Excel: ' . $e->getMessage())
                ->danger()
                ->send();

            return null;
        }
    }

    public static function getCurrentStockStatistik($barang)
    {
        $today = now();

        return [
            'total_tersedia' => Barang::where('status', 'Tersedia')->count(),
            'total_terjual' => Barang::where('status', 'Terjual')->count(),
            'total_terdonasi' => Barang::where('status', 'Terdonasi')->count(),
            'total_diperpanjang' => $barang->where('opsi', 'Diperpanjang')->count(),
            'total_dengan_hunter' => $barang->whereNotNull('id_hunter_pegawai')->count(),
            'barang_baru' => $barang->filter(function ($item) use ($today) {
                return $item->tanggal_masuk && Carbon::parse($item->tanggal_masuk)->diffInDays($today) < 30;
            })->count(),
            'barang_lama' => $barang->filter(function ($item) use ($today) {
                return $item->tanggal_masuk && Carbon::parse($item->tanggal_masuk)->diffInDays($today) > 60;
            })->count(),
            'mendekati_batas' => $barang->filter(function ($item) use ($today) {
                return $item->tanggal_batas && Carbon::parse($item->tanggal_batas)->diffInDays($today) <= 7;
            })->count(),
            'lewat_batas' => $barang->filter(function ($item) use ($today) {
                return $item->tanggal_batas && Carbon::parse($item->tanggal_batas)->isPast();
            })->count(),
        ];
    }

    public static function getCurrentStockByCategory()
    {
        return DB::table('barang')
            ->join('kategori_barang', 'barang.id_kategori', '=', 'kategori_barang.id_kategori')
            ->select(
                'kategori_barang.nama_kategori',
                DB::raw('COUNT(*) as jumlah'),
                DB::raw('SUM(harga) as total_nilai')
            )
            ->where('barang.status', 'Tersedia')
            ->groupBy('kategori_barang.nama_kategori')
            ->orderBy('jumlah', 'desc')
            ->get();
    }

    public static function getCurrentStockByPenitip()
    {
        return DB::table('barang')
            ->join('penitip', 'barang.id_penitip', '=', 'penitip.id_penitip')
            ->select(
                'penitip.nama_penitip',
                DB::raw('COUNT(*) as jumlah_barang'),
                DB::raw('SUM(CASE WHEN barang.status = "Tersedia" THEN 1 ELSE 0 END) as tersedia'),
                DB::raw('SUM(CASE WHEN barang.status = "Terjual" THEN 1 ELSE 0 END) as terjual'),
                DB::raw('SUM(harga) as total_nilai')
            )
            ->groupBy('penitip.nama_penitip')
            ->orderBy('jumlah_barang', 'desc')
            ->limit(10)
            ->get();
    }

    public static function getBarangMendekatiBatas()
    {
        $today = now();

        return Barang::with(['penitip', 'kategori'])
            ->where('status', 'Tersedia')
            ->whereNotNull('tanggal_batas')
            ->where('tanggal_batas', '<=', $today->copy()->addDays(30))
            ->orderBy('tanggal_batas', 'asc')
            ->get();
    }
}
