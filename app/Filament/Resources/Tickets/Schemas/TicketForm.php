<?php

namespace App\Filament\Resources\Tickets\Schemas;

use Filament\Forms\Components\DateTimePicker;
use Filament\Forms\Components\Select;
use Filament\Forms\Components\Textarea;
use Filament\Forms\Components\TextInput;
use Filament\Schemas\Schema;

class TicketForm
{
    public static function configure(Schema $schema): Schema
    {
        return $schema
            ->columns(2)
            ->components([
                TextInput::make('title')
                    ->label('Titre')
                    ->required()
                    ->columnSpanFull(),
                Textarea::make('description')
                    ->label('Description')
                    ->required()
                    ->columnSpanFull(),
                Select::make('type')
                    ->label('Type')
                    ->options([
                        'incident' => 'Incident',
                        'service_request' => 'Demande de service',
                        'problem' => 'Problème',
                        'change' => 'Changement',
                    ])
                    ->required(),
                Select::make('status')
                    ->label('Statut')
                    ->options([
                        'open' => 'Ouvert',
                        'pending_approval' => 'Approbation requise',
                        'assigned' => 'Assigné',
                        'in_progress' => 'En cours',
                        'pending' => 'En attente',
                        'resolved' => 'Résolu',
                        'closed' => 'Fermé',
                        'cancelled' => 'Annulé',
                    ])
                    ->required()
                    ->default('open')
                    ->disabledOn('edit'),
                Select::make('priority')
                    ->label('Priorité')
                    ->options([
                        'low' => 'Basse',
                        'medium' => 'Moyenne',
                        'high' => 'Haute',
                        'critical' => 'Critique',
                    ])
                    ->required()
                    ->default('medium'),
                Select::make('category_id')
                    ->label('Catégorie')
                    ->relationship('category', 'name'),
                Select::make('requester_id')
                    ->label('Demandeur')
                    ->relationship('requester', 'name')
                    ->searchable()
                    ->required(),
                Select::make('assigned_agent_id')
                    ->label('Technicien')
                    ->relationship('assignedAgent', 'name')
                    ->searchable(),
                Select::make('sla_policy_id')
                    ->label('Politique SLA')
                    ->relationship('slaPolicy', 'name'),
                DateTimePicker::make('sla_response_due_at')
                    ->label('SLA réponse due'),
                DateTimePicker::make('sla_resolution_due_at')
                    ->label('SLA résolution due'),
                DateTimePicker::make('resolved_at')
                    ->label('Résolu le'),
                DateTimePicker::make('closed_at')
                    ->label('Fermé le'),
            ]);
    }
}
