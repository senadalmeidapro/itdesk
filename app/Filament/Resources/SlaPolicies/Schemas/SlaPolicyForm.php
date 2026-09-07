<?php

namespace App\Filament\Resources\SlaPolicies\Schemas;

use Filament\Forms\Components\Select;
use Filament\Forms\Components\TextInput;
use Filament\Schemas\Schema;

class SlaPolicyForm
{
    public static function configure(Schema $schema): Schema
    {
        return $schema
            ->columns(2)
            ->components([
                TextInput::make('name')
                    ->label('Nom')
                    ->required()
                    ->columnSpan(2),
                Select::make('priority')
                    ->label('Priorité')
                    ->options([
                        'low' => 'Basse',
                        'medium' => 'Moyenne',
                        'high' => 'Haute',
                        'critical' => 'Critique',
                    ])
                    ->required(),
                TextInput::make('response_time_minutes')
                    ->label('Temps de réponse (min)')
                    ->required()
                    ->numeric(),
                TextInput::make('resolution_time_minutes')
                    ->label('Temps de résolution (min)')
                    ->required()
                    ->numeric(),
            ]);
    }
}
