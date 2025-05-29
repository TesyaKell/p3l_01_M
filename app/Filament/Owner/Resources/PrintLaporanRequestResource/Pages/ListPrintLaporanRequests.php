<?php

namespace App\Filament\Owner\Resources\PrintLaporanRequestResource\Pages;

use App\Filament\Owner\Resources\PrintLaporanRequestResource;
use Filament\Resources\Pages\Page;
use App\Models\RequestDonasi;

class ListPrintLaporanRequests extends Page
{
    protected static string $resource = PrintLaporanRequestResource::class;

    protected static string $view = 'laporan.print-laporan-request';

    public $requestsDiproses;
    public $requestsDiterima;

    public function mount(): void
    {
        $this->requestsDiproses = RequestDonasi::where('status', 'Diproses')->with('organisasi')->get();
        $this->requestsDiterima = RequestDonasi::where('status', 'Diterima')->with('organisasi')->get();
    }
}
