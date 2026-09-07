<?php

namespace App\Filament\Resources\SoftwareLicenses\Schemas;

use Filament\Forms\Components\DatePicker;
use Filament\Forms\Components\Select;
use Filament\Forms\Components\Textarea;
use Filament\Forms\Components\TextInput;
use Filament\Schemas\Schema;

class SoftwareLicenseForm
{
    public static function configure(Schema $schema): Schema
    {
        return $schema
            ->columns(2)
            ->components([
                TextInput::make('name')
                    ->label('Nom du logiciel')
                    ->required(),
                TextInput::make('vendor')
                    ->label('Éditeur'),
                TextInput::make('license_key')
                    ->label('Clé de licence'),
                TextInput::make('seats_total')
                    ->label('Sièges totaux')
                    ->required()
                    ->numeric()
                    ->default(1),
                TextInput::make('seats_used')
                    ->label('Sièges utilisés')
                    ->required()
                    ->numeric()
                    ->default(0),
                DatePicker::make('purchased_at')
                    ->label("Date d'achat"),
                DatePicker::make('expires_at')
                    ->label('Expire le'),
                Select::make('department_id')
                    ->label('Département')
                    ->relationship('department', 'name'),
                Textarea::make('notes')
                    ->label('Notes')
                    ->columnSpanFull(),
            ]);
    }
}
