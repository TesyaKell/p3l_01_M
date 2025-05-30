<?php

namespace App\Filament\QC\Resources\BarangResource\Pages;

use App\Filament\QC\Resources\BarangResource;
use Filament\Resources\Pages\CreateRecord;
use Filament\Notifications\Notification;
use Filament\Actions;

class CreateBarang extends CreateRecord
{
    protected static string $resource = BarangResource::class;

    protected function mutateFormDataBeforeCreate(array $data): array
    {
        if (!empty($data['barangs'])) {
            $barangData = $data['barangs'][0]; // Ambil data dari Repeater
            $barangData['id_penitip'] = $data['id_penitip'];
            return $barangData; // Kembalikan data tunggal untuk disimpan
        }
        return $data;
    }

    protected function getCreateFormAction(): Actions\Action
    {
        return Actions\Action::make('create')
            ->label('Create')
            ->requiresConfirmation()
            ->modalHeading('Konfirmasi Penyimpanan')
            ->modalDescription('Apakah Anda yakin ingin menyimpan barang baru ini?')
            ->modalSubmitActionLabel('Ya, Simpan')
            ->modalCancelActionLabel('Batal')
            ->action(function () {
                $this->create();
            })
            ->successNotification(
                Notification::make()
                    ->success()
                    ->title('Barang berhasil ditambahkan')
                    ->body('Barang baru telah disimpan.')
            );
    }

    protected function getCreatedNotification(): ?Notification
    {
        return null;
    }
}
