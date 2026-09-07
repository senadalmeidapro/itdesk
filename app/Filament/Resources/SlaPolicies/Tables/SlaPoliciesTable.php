<?php

namespace App\Filament\Resources\SlaPolicies\Tables;

use Filament\Actions\BulkActionGroup;
use Filament\Actions\DeleteBulkAction;
use Filament\Actions\EditAction;
use Filament\Tables\Columns\TextColumn;
use Filament\Tables\Table;

class SlaPoliciesTable
{
    public static function configure(Table $table): Table
    {
        return $table
            ->defaultSort('name')
            ->columns([
                TextColumn::make('name')
                    ->label('Nom')
                    ->searchable(),
                TextColumn::make('priority')
                    ->label('Priorité')
                    ->badge()
                    ->color(fn (string $state): ?string => match ($state) {
                        'low' => 'gray',
                        'medium' => 'sky',
                        'high' => 'amber',
                        'critical' => 'danger',
                        default => 'gray',
                    }),
                TextColumn::make('response_time_minutes')
                    ->label('Réponse (min)')
                    ->numeric()
                    ->sortable(),
                TextColumn::make('resolution_time_minutes')
                    ->label('Résolution (min)')
                    ->numeric()
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
