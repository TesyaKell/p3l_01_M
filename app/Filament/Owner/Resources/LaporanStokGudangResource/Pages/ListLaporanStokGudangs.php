<?php

namespace App\Filament\Owner\Resources\LaporanStokGudangResource\Pages;

use App\Filament\Owner\Resources\LaporanStokGudangResource;
use Filament\Actions;
use Filament\Resources\Pages\ListRecords;
use Filament\Notifications\Notification;

class ListLaporanStokGudangs extends ListRecords
{
    protected static string $resource = LaporanStokGudangResource::class;

    protected function getHeaderActions(): array
    {
        return [
            Actions\Action::make('export_gudang_report')
                ->label('Export Stok Gudang Harian ')
                ->icon('heroicon-o-document-text')
                ->color('primary')
                ->action(function () {
                    try {
                        return $this->getResource()::generateGudangReport();
                    } catch (\Exception $e) {
                        Notification::make()
                            ->title('Error')
                            ->body('Gagal menggenerate laporan gudang: ' . $e->getMessage())
                            ->danger()
                            ->send();

                        return null;
                    }
                }),

            // Actions\Action::make('export_harian_lengkap_report')
            //     ->label('Export Stok Gudang')
            //     ->icon('heroicon-o-calendar-days')
            //     ->color('success')
            //     ->action(function () {
            //         try {
            //             return $this->getResource()::generateDailyReport();
            //         } catch (\Exception $e) {
            //             Notification::make()
            //                 ->title('Error')
            //                 ->body('Gagal menggenerate laporan harian lengkap: ' . $e->getMessage())
            //                 ->danger()
            //                 ->send();

            //             return null;
            //         }
            //     }),


            // Actions\Action::make('refresh_data')
            //     ->label('Refresh Data')
            //     ->icon('heroicon-o-arrow-path')
            //     ->color('gray')
            //     ->action(function () {
            //         Notification::make()
            //             ->title('Data Direfresh')
            //             ->body('Data stok gudang telah diperbarui.')
            //             ->success()
            //             ->send();

            //         return redirect(request()->header('Referer'));
            //     }),

        ];
    }

    protected function getHeaderWidgets(): array
    {
        return [
            LaporanStokGudangResource\Widgets\CurrentStockOverviewWidget::class,
            LaporanStokGudangResource\Widgets\StockByCategoryChart::class,
            LaporanStokGudangResource\Widgets\StockByStatusChart::class,

        ];
    }
}
