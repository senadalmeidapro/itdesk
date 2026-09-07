<?php

namespace App\Filament\Resources\SoftwareLicenses\Tables;

use Filament\Actions\BulkActionGroup;
use Filament\Actions\DeleteBulkAction;
use Filament\Actions\EditAction;
use Filament\Tables\Columns\TextColumn;
use Filament\Tables\Table;

class SoftwareLicensesTable
{
    public static function configure(Table $table): Table
    {
        return $table
            ->defaultSort('name')
            ->columns([
                TextColumn::make('name')
                    ->label('Logiciel')
                    ->searchable(),
                TextColumn::make('vendor')
                    ->label('Éditeur')
                    ->searchable(),
                TextColumn::make('license_key')
                    ->label('Clé')
                    ->searchable()
                    ->toggleable(isToggledHiddenByDefault: true),
                TextColumn::make('seats_total')
                    ->label('Sièges')
                    ->numeric()
                    ->sortable(),
                TextColumn::make('seats_used')
                    ->label('Utilisés')
                    ->numeric()
                    ->sortable(),
                TextColumn::make('expires_at')
                    ->label('Expire le')
                    ->date()
                    ->sortable(),
                TextColumn::make('department.name')
                    ->label('Département')
                    ->searchable(),
            ])
            ->recordActions([
                EditAction::make(),
            ])
            ->toolbarActions([
                BulkActionGroup::make([
                    DeleteBulkAction::make(),
                ]),
            ]);
    }
}
