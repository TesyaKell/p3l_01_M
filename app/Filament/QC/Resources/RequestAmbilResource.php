<?php

namespace App\Filament\QC\Resources;

use App\Filament\QC\Resources\RequestAmbilResource\Pages;
use App\Filament\QC\Resources\RequestAmbilResource\RelationManagers;
use App\Models\Barang;
use App\Models\Penitip;
use Filament\Forms;
use Filament\Forms\Form;
use Filament\Notifications\Notification;
use Filament\Resources\Resource;
use Filament\Tables;
use Filament\Tables\Table;
use Filament\Tables\Actions\Action;
use App\Http\Controllers\BarangController;
use Illuminate\Support\Carbon;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\SoftDeletingScope;

class RequestAmbilResource extends Resource
{
    protected static ?string $model = Barang::class;

    protected static ?string $navigationIcon = 'heroicon-o-rectangle-stack';

    protected static ?string $navigationLabel = 'Request Ambil Barang';
    protected static ?string $navigationGroup = 'Manajemen Jadwal';
    public static function form(Form $form): Form
    {
        return $form
            ->schema([
                //
            ]);
    }
    public static function getEloquentQuery(): Builder
    {
        return parent::getEloquentQuery()
            ->where('status', 'Diambil')
            ->whereNull('tanggal_ambil');
    }

    public static function table(Table $table): Table
    {
        return $table
            ->columns([
                Tables\Columns\TextColumn::make('nama_barang')->label('Nama')->searchable(),
                Tables\Columns\TextColumn::make('kategori.nama_kategori')->label('Kategori')->searchable(),
                Tables\Columns\TextColumn::make('penitip.nama_penitip')->label('Penitip')->searchable(),
                Tables\Columns\TextColumn::make('status')->badge(),
                Tables\Columns\TextColumn::make('opsi')->label('Opsi')->placeholder('-'),
                Tables\Columns\TextColumn::make('harga')->label('Harga (Rp)')->money('IDR'),
                Tables\Columns\TextColumn::make('berat_barang')->label('Berat (Kg)')->numeric(),
                Tables\Columns\BooleanColumn::make('garansi')->label('Garansi'),
                Tables\Columns\TextColumn::make('batas_garansi')->label('Batas Garansi')->dateTime()->sortable()->placeholder('Kosong'),
                Tables\Columns\TextColumn::make('tanggal_masuk')->label('Tanggal Masuk')->dateTime()->sortable(),
                Tables\Columns\TextColumn::make('tanggal_akhir')->label('Tanggal Akhir')->dateTime()->sortable(),
                Tables\Columns\TextColumn::make('tanggal_batas')->label('Tanggal Batas')->dateTime()->sortable(),
            ])

            ->filters([
                Tables\Filters\Filter::make('tanggal_masuk')
                    ->form([
                        Forms\Components\DatePicker::make('dari_tanggal'),
                        Forms\Components\DatePicker::make('sampai_tanggal'),
                    ])
                    ->query(function (Builder $query, array $data): Builder {
                        return $query
                            ->when($data['dari_tanggal'], fn ($query, $date) => $query->whereDate('tanggal_masuk', '>=', $date))
                            ->when($data['sampai_tanggal'], fn ($query, $date) => $query->whereDate('tanggal_masuk', '<=', $date));
                    }),
            ])
            ->actions([
                Action::make('terima')
                    ->label('Terima')
                    ->color('success')
                    ->requiresConfirmation()
                    ->action(function ($record) {
                        app(BarangController::class)->terimaBarangDiambil($record->kode_barang);
                        Notification::make()
                                ->title('Berhasil Diambil')
                                ->body('Pengambilan Barang oleh Penitip Berhasil')
                                ->success()
                                ->send();
                    }),

                Action::make('tolak')
                    ->label('Tolak')
                    ->color('danger')
                    ->requiresConfirmation()
                    ->action(function ($record) {
                        app(BarangController::class)->tolakBarangDiambil($record->kode_barang);
                        Notification::make()
                                ->title('Berhasil Didonasikan')
                                ->body('Barang Berhasil Didonasikan')
                                ->success()
                                ->send();
                    }),
                Tables\Actions\EditAction::make(),
            ])
            ->bulkActions([
                Tables\Actions\BulkActionGroup::make([
                    Tables\Actions\DeleteBulkAction::make(),
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
            'index' => Pages\ListRequestAmbils::route('/'),
            'create' => Pages\CreateRequestAmbil::route('/create'),
            'edit' => Pages\EditRequestAmbil::route('/{record}/edit'),
        ];
    }
}
