<?php

namespace App\Filament\Owner\Resources;

use App\Filament\Owner\Resources\LaporanPenjualanKategoriResource\Pages;
use App\Filament\Owner\Resources\LaporanPenjualanKategoriResource\RelationManagers;
use App\Models\LaporanPenjualanKategori;
use Filament\Forms;
use Filament\Forms\Form;
use Filament\Pages\Actions\Action;
use Filament\Resources\Resource;
use Filament\Tables;
use Filament\Tables\Filters\SelectFilter;
use Filament\Tables\Table;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\SoftDeletingScope;

class LaporanPenjualanKategoriResource extends Resource
{
    protected static ?string $model = LaporanPenjualanKategori::class;

    protected static ?string $navigationIcon = 'heroicon-o-rectangle-stack';

    protected static ?string $navigationLabel = 'Laporan Penjualan Per Kategori';
    protected static ?string $navigationGroup = 'Laporan';

    public static function form(Form $form): Form
    {
        return $form
            ->schema([
                //
            ]);
    }

    public static function table(Table $table): Table
    {
        return $table
            ->columns([
                Tables\Columns\TextColumn::make('kategori.nama_kategori')
                    ->label('Kategori')
                    ->placeholder('-'),

                Tables\Columns\TextColumn::make('terjual')
                    ->label('Jumlah item terjual')
                    ->placeholder('0'),

                Tables\Columns\TextColumn::make('gagal')
                    ->label('Jumlah item gagal terjual')
                    ->placeholder('0'),
            ])
            ->filters([
                SelectFilter::make('tanggal_tahun')
                ->label('tahun')
                ->default()
                ->options(range(now()->year,now()->year-5)) 
                ->mapWithKeys(fn($y) => [$y => $y])
            ])
            ->actions([
                Action::make('export')
                    ->label('Unduh PDF')
                    ->color('success')
                    ->icon('heroicon-o-document')
                    ->url(fn () => route('laporan-penjualan-kategori.pdf', ['tahun' => request()->input('tableFilters.tahun') ?? now()->year]))
                    ->openUrlInNewTab()
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
            'index' => Pages\ListLaporanPenjualanKategoris::route('/'),
            'create' => Pages\CreateLaporanPenjualanKategori::route('/create'),
            'edit' => Pages\EditLaporanPenjualanKategori::route('/{record}/edit'),
        ];
    }
}
