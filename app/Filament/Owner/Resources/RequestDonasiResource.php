<?php

namespace App\Filament\Owner\Resources;

use App\Filament\Owner\Resources\RequestDonasiResource\Pages;
use App\Models\RequestDonasi;
use Filament\Forms;
use Filament\Forms\Form;
use Filament\Resources\Resource;
use Filament\Tables;
use Filament\Tables\Table;
use Illuminate\Database\Eloquent\Builder;

class RequestDonasiResource extends Resource
{
    protected static ?string $model = RequestDonasi::class;
    protected static ?string $navigationIcon = 'heroicon-o-gift';
    protected static ?string $navigationGroup = 'Donasi Management';
    protected static ?string $navigationLabel = 'Permintaan Donasi';
    protected static ?int $navigationSort = 1;



    public static function form(Form $form): Form
    {
        return $form->schema([
            Forms\Components\Select::make('id_organisasi')
                ->relationship('organisasi', 'nama_organisasi')
                ->required()
                ->searchable()
                ->preload(),

            Forms\Components\Textarea::make('desk_request')
                ->required()
                ->columnSpanFull(),

            // Tampilkan created_at sebagai readonly
            Forms\Components\DatePicker::make('created_at')
                ->label('Tanggal Request')
                ->disabled()
                ->dehydrated(false) // agar tidak dikirim saat submit
                ->default(now()),

            Forms\Components\Select::make('status')
                ->options([
                    'pending' => 'Pending',
                    'approved' => 'Disetujui',
                    'rejected' => 'Ditolak',
                    'completed' => 'Selesai',
                ])
                ->required(),
        ]);
    }

    public static function table(Table $table): Table
    {
        return $table
            ->columns([
                Tables\Columns\TextColumn::make('id_request')
                    ->searchable()
                    ->sortable(),

                Tables\Columns\TextColumn::make('organisasi.nama_organisasi')
                    ->label('Organisasi')
                    ->searchable()
                    ->sortable(),

                Tables\Columns\TextColumn::make('desk_request')
                    ->label('Deskripsi')
                    ->searchable(),

                Tables\Columns\TextColumn::make('created_at')
                    ->label('Tanggal Request')
                    ->dateTime('d M Y H:i')
                    ->sortable(),

                Tables\Columns\BadgeColumn::make('status')
                    ->label('Status')
                    ->colors([
                        'warning' => 'pending',
                        'success' => 'approved',
                        'danger' => 'rejected',
                        'primary' => 'completed',
                    ])
                    ->formatStateUsing(fn (string $state) => match ($state) {
                        'pending' => 'Pending',
                        'approved' => 'Disetujui',
                        'rejected' => 'Ditolak',
                        'completed' => 'Selesai',
                        default => ucfirst($state),
                    }),

            ])
            ->filters([
                Tables\Filters\SelectFilter::make('status')
                    ->options([
                        'pending' => 'Pending',
                        'approved' => 'Disetujui',
                        'rejected' => 'Ditolak',
                        'completed' => 'Selesai',
                    ]),

                Tables\Filters\SelectFilter::make('organisasi')
                    ->relationship('organisasi', 'nama_organisasi'),
            ])
            ->actions([
                Tables\Actions\ViewAction::make(),
            ])
            ->bulkActions([]);
    }

    public static function getRelations(): array
    {
        return [];
    }

    public static function getPages(): array
    {
        return [
            'index' => Pages\ListRequestDonasis::route('/'),
            'view' => Pages\ViewRequestDonasi::route('/{record}'),
        ];
    }
}
