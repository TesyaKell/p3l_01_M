<?php

namespace App\Filament\Owner\Resources\PrintLaporanTransaksiPenitipResource\Pages;

use App\Filament\Owner\Resources\PrintLaporanTransaksiPenitipResource;
use Filament\Resources\Pages\ListRecords;
use Filament\Actions\Action;
use Filament\Forms\Components\Select;
use Filament\Forms\Components\TextInput;
use Barryvdh\DomPDF\Facade\Pdf;
use App\Models\Barang;
use Illuminate\Support\Facades\Response;
use Carbon\Carbon;
use App\Models\Penitip;

class ListPrintLaporanTransaksiPenitips extends ListRecords
{
    protected static string $resource = PrintLaporanTransaksiPenitipResource::class;

    protected function getHeaderActions(): array
    {
        return [
            Action::make('unduh_pdf_penitip')
                ->label('Unduh PDF Laporan Transaksi Penitip')
                ->button()
                ->form([
                    Select::make('penitip_id')
                        ->label('Pilih Penitip')
                        ->options(Penitip::all()->pluck('nama_penitip', 'id_penitip'))
                        ->searchable()
                        ->required(),

                    Select::make('bulan')
                        ->label('Bulan')
                        ->options([
                            1 => 'Januari',
                            2 => 'Februari',
                            3 => 'Maret',
                            4 => 'April',
                            5 => 'Mei',
                            6 => 'Juni',
                            7 => 'Juli',
                            8 => 'Agustus',
                            9 => 'September',
                            10 => 'Oktober',
                            11 => 'November',
                            12 => 'Desember',
                        ])
                        ->required()
                        ->default(date('n')),

                    TextInput::make('tahun')
                        ->label('Tahun')
                        ->numeric()
                        ->required()
                        ->default(date('Y')),
                ])
                ->action(function (array $data): \Symfony\Component\HttpFoundation\StreamedResponse {
                    $penitipId = $data['penitip_id'];
                    $bulan = (int) $data['bulan'];
                    $tahun = (int) $data['tahun'];

                    $barangList = Barang::with('penitip')
                        ->where('id_penitip', $penitipId)
                        ->where('status', 'terjual')
                        ->whereMonth('tanggal_laku', $bulan)
                        ->whereYear('tanggal_laku', $tahun)
                        ->get();

                    $penitip = $barangList->first()?->penitip;

                    $pdf = Pdf::loadView('laporan.laporan_transaksi_penitip', [
                        'barangGrouped' => collect([$penitipId => $barangList]),
                        'bulan' => $bulan,
                        'tahun' => $tahun,
                        'penitip' => $penitip,
                        'tanggalCetak' => now()->translatedFormat('d F Y'),
                    ]);

                    return Response::streamDownload(function () use ($pdf) {
                        echo $pdf->output();
                    }, "laporan-transaksi-penitip-{$penitipId}-{$bulan}-{$tahun}.pdf");
                }),
        ];
    }

    // Override actions pada table supaya tidak ada tombol unduh pdf di tiap baris
    protected function getTableActions(): array
    {
        return [];
    }
}
