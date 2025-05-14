<?php

namespace App\Filament\Owner\Resources;

use App\Filament\Owner\Resources\DonasiResource\Pages;
use App\Models\Donasi;
use Filament\Forms;
use Filament\Forms\Form;
use Filament\Resources\Resource;
use Filament\Tables;
use Filament\Tables\Table;
use Illuminate\Database\Eloquent\Builder;

class DonasiResource extends Resource
{
    protected static ?string $model = Donasi::class;

    protected static ?string $navigationIcon = 'heroicon-o-rectangle-stack';



    public static function table(Table $table): Table
    {
        return $table
            ->columns([
                Tables\Columns\TextColumn::make('request.organisasi.nama_organisasi')
                    ->label('Nama Organisasi')
                    ->searchable(),
                Tables\Columns\TextColumn::make('request.desk_request')
                    ->label('Deskripsi Request Donasi')
                    ->searchable(),
                // Tables\Columns\TextColumn::make('nama_penitip')
                //     ->label('Nama Penitip')
                //     ->searchable(),
                // Tables\Columns\TextColumn::make('tanggal_donasi')
                //     ->label('Tanggal Donasi')
                //     ->searchable(),
                Tables\Columns\BadgeColumn::make('request.status')  // Mengakses status melalui relasi request
                    ->label('Status')

                    ->searchable(),
            ])
            ->paginationPageOptions([10, 25, 50, 100])
            ->filters([

            ])
        ->actions([
        Tables\Actions\Action::make('Kirim Donasi')
    ->label('Kirim Donasi')
    ->color('success')
    ->icon('heroicon-o-truck')
    ->visible(fn ($record) => $record->request->status === 'Diproses')
    ->form([
        Forms\Components\TextInput::make('nama_penerima')
            ->label('Nama Penerima')
            ->required(),
    ])
    ->requiresConfirmation()
    ->action(function ($record, array $data) {
        // Update status pada relasi request
        $record->request->update(['status' => 'Diterima']);

        // Simpan tanggal donasi dan nama penerima
        $record->update([
            'tanggal_donasi' => now(),
            'nama_penerima' => $data['nama_penerima'],
        ]);

        // Update poin & saldo penitip
        $penitip = $record->penitip;
        if ($penitip) {
            $penitip->poin += 1;
            $penitip->saldo += 10000;
            $penitip->save();
        }
    }),

])

            ->bulkActions([
            Tables\Actions\BulkActionGroup::make([
                    Tables\Actions\DeleteBulkAction::make(),
            ]),
        ]);
    }

    public static function canCreate(): bool
    {
        return false;
    }

    public static function getEloquentQuery(): Builder
    {
        return parent::getEloquentQuery()
            ->whereHas('request', function ($query) {
                $query->where('status', 'Diproses');
            });
    }
    public static function getPages(): array
    {
        return [
            'index' => Pages\ListDonasis::route('/'),
        ];
    }
}
