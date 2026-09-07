<?php

namespace App\Filament\Resources\Assets\Tables;

use Filament\Actions\BulkActionGroup;
use Filament\Actions\DeleteBulkAction;
use Filament\Actions\EditAction;
use Filament\Tables\Columns\TextColumn;
use Filament\Tables\Table;

class AssetsTable
{
    public static function configure(Table $table): Table
    {
        return $table
            ->defaultSort('asset_tag')
            ->columns([
                TextColumn::make('asset_tag')
                    ->label('Tag')
                    ->searchable(),
                TextColumn::make('name')
                    ->label('Nom')
                    ->searchable(),
                TextColumn::make('type')
                    ->label('Type')
                    ->formatStateUsing(fn (string $state): string => str_replace('_', ' ', ucfirst($state)))
                    ->badge()
                    ->color('gray'),
                TextColumn::make('status')
                    ->label('Statut')
                    ->formatStateUsing(fn (string $state): string => str_replace('_', ' ', ucfirst($state)))
                    ->badge()
                    ->color(fn (string $state): ?string => match ($state) {
                        'in_use' => 'success',
                        'repair' => 'warning',
                        default => 'gray',
                    }),
                TextColumn::make('assignedUser.name')
                    ->label('Assigné à')
                    ->searchable(),
                TextColumn::make('category.name')
                    ->label('Catégorie'),
                TextColumn::make('serial_number')
                    ->label('Série')
                    ->searchable()
                    ->toggleable(isToggledHiddenByDefault: true),
                TextColumn::make('ip_address')
                    ->label('IP')
                    ->searchable()
                    ->toggleable(),
                TextColumn::make('location')
                    ->label('Emplacement')
                    ->searchable()
                    ->toggleable(),
                TextColumn::make('purchase_date')
                    ->label('Acheté le')
                    ->date()
                    ->sortable()
                    ->toggleable(isToggledHiddenByDefault: true),
                TextColumn::make('warranty_expires_at')
                    ->label('Fin de garantie')
                    ->date()
                    ->sortable(),
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
