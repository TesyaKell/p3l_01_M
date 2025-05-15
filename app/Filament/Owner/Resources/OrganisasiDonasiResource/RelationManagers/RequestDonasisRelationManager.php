<?php

namespace App\Filament\Owner\Resources\OrganisasiDonasiResource\RelationManagers;

use Filament\Forms;
use Filament\Forms\Form;
use Filament\Resources\RelationManagers\RelationManager;
use Filament\Tables;
use Filament\Tables\Table;

class RequestDonasisRelationManager extends RelationManager
{
    protected static string $relationship = 'requestDonasis';

    public function form(Form $form): Form
    {
        return $form
            ->schema([
                Forms\Components\Textarea::make('desk_request')  // sesuaikan dengan nama field sebenarnya
                    ->required()
                    ->columnSpanFull(),

                Forms\Components\Select::make('status')
                    ->options([
                        'pending' => 'Pending',
                        'approved' => 'Approved',
                        'rejected' => 'Rejected',
                        'completed' => 'Completed',
                    ])
                    ->required(),
            ]);
    }

    public function table(Table $table): Table
    {
        return $table
            ->recordTitleAttribute('desk_request')  // ini untuk judul record, bisa diganti
            ->columns([
                Tables\Columns\TextColumn::make('id_request')
                    ->searchable()
                    ->sortable(),

                Tables\Columns\TextColumn::make('desk_request')
                    ->searchable()
                    ->limit(50),


                Tables\Columns\BadgeColumn::make('status')
                    ->colors([

                        'success' => 'Diterima',
                        'primary' => 'Diproses',
                    ]),
            ])
            ->filters([
                Tables\Filters\SelectFilter::make('status')
                    ->options([
                         'success' => 'Diterima',
                        'primary' => 'Diproses',
                    ]),
            ])
            ->headerActions([
                Tables\Actions\CreateAction::make(),
            ])
            ->actions([
                Tables\Actions\EditAction::make(),
                Tables\Actions\DeleteAction::make(),
            ])
            ->bulkActions([
                Tables\Actions\BulkActionGroup::make([
                    Tables\Actions\DeleteBulkAction::make(),
                ]),
            ]);
    }
}
