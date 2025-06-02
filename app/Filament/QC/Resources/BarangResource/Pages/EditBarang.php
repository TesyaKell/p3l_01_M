<?php

namespace App\Filament\QC\Resources\BarangResource\Pages;

use App\Filament\QC\Resources\BarangResource;
use Filament\Actions;
use Filament\Forms;
use Filament\Resources\Pages\EditRecord;
use Filament\Notifications\Notification;

class EditBarang extends EditRecord
{
    protected static string $resource = BarangResource::class;

    protected function getHeaderActions(): array
    {
        return [
            Actions\DeleteAction::make(),
        ];
    }

    protected function mutateFormDataBeforeFill(array $data): array
    {
        return [
            'id_penitip' => $data['id_penitip'],
            'barangs' => [$data],
        ];
    }

    protected function mutateFormDataBeforeSave(array $data): array
    {
        $barangData = $data['barangs'][0];
        if (isset($data['id_penitip'])) {
            $barangData['id_penitip'] = $data['id_penitip'];
        }

        return $barangData;
    }


    protected function handleRecordUpdate(\Illuminate\Database\Eloquent\Model $record, array $data): \Illuminate\Database\Eloquent\Model
    {
        if (isset($data['foto_produk'])) {
            $record->foto_produk = $data['foto_produk'];
        }
        $record->update($data);
        return $record;
    }

    protected function getSaveFormAction(): Actions\Action
    {
        return Actions\Action::make('save')
            ->label('Save changes')
            ->requiresConfirmation()
            ->modalHeading('Konfirmasi Penyimpanan')
            ->modalDescription('Apakah Anda yakin ingin menyimpan perubahan pada barang ini?')
            ->modalSubmitActionLabel('Ya, Simpan')
            ->modalCancelActionLabel('Batal')
            ->action(function () {
                $data = $this->form->getState();
                $this->handleRecordUpdate($this->getRecord(), $data);
                return redirect()->route('filament.qc.resources.barangs.index');
            })
            ->successNotification(
                Notification::make()
                    ->success()
                    ->title('Barang berhasil diperbarui')
                    ->body('Perubahan pada barang telah disimpan.')
            );
    }

    protected function getSavedNotification(): ?Notification
    {
        return null; // Diganti dengan notifikasi di getSaveFormAction
    }
}
