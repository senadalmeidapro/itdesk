<?php

namespace App\Filament\Resources\Categories\Schemas;

use Filament\Forms\Components\Select;
use Filament\Forms\Components\TextInput;
use Filament\Schemas\Schema;

class CategoryForm
{
    public static function configure(Schema $schema): Schema
    {
        return $schema
            ->components([
                TextInput::make('name')
                    ->label('Nom')
                    ->required(),
                Select::make('department_id')
                    ->label('Département')
                    ->relationship('department', 'name'),
                Select::make('default_sla_policy_id')
                    ->label('Politique SLA par défaut')
                    ->relationship('defaultSlaPolicy', 'name'),
            ]);
    }
}
