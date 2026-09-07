<?php

namespace App\Filament\Resources\Tickets\Tables;

use Filament\Actions\BulkActionGroup;
use Filament\Actions\DeleteBulkAction;
use Filament\Actions\EditAction;
use Filament\Tables\Columns\TextColumn;
use Filament\Tables\Table;

class TicketsTable
{
    public static function configure(Table $table): Table
    {
        return $table
            ->defaultSort('created_at', 'desc')
            ->columns([
                TextColumn::make('id')
                    ->label('#')
                    ->sortable(),
                TextColumn::make('title')
                    ->label('Titre')
                    ->searchable()
                    ->wrap(),
                TextColumn::make('type')
                    ->label('Type')
                    ->formatStateUsing(fn (string $state): string => str_replace('_', ' ', ucfirst($state)))
                    ->badge()
                    ->color('info'),
                TextColumn::make('status')
                    ->label('Statut')
                    ->formatStateUsing(fn (string $state): string => str_replace('_', ' ', ucfirst($state)))
                    ->badge()
                    ->color(fn (string $state): ?string => match ($state) {
                        'open', 'assigned' => 'info',
                        'pending_approval', 'in_progress', 'pending' => 'warning',
                        'resolved' => 'success',
                        default => 'gray',
                    }),
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
                TextColumn::make('category.name')
                    ->label('Catégorie')
                    ->searchable(),
                TextColumn::make('requester.name')
                    ->label('Demandeur')
                    ->searchable(),
                TextColumn::make('assignedAgent.name')
                    ->label('Technicien')
                    ->searchable(),
                TextColumn::make('sla_resolution_due_at')
                    ->label('SLA résolution due')
                    ->dateTime()
                    ->sortable(),
                TextColumn::make('created_at')
                    ->label('Créé le')
                    ->dateTime()
                    ->sortable()
                    ->toggleable(),
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
