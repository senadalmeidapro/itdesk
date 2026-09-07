<?php

namespace App\Filament\Resources\ContactMessages\Schemas;

use Filament\Forms\Components\Select;
use Filament\Forms\Components\Textarea;
use Filament\Forms\Components\TextInput;
use Filament\Forms\Components\Toggle;
use Filament\Schemas\Schema;

class ContactMessageForm
{
    public static function configure(Schema $schema): Schema
    {
        return $schema
            ->columns(2)
            ->components([
                TextInput::make('name')
                    ->label('Nom')
                    ->required(),
                TextInput::make('email')
                    ->label('Adresse e-mail')
                    ->email()
                    ->required(),
                TextInput::make('phone')
                    ->label('Téléphone')
                    ->tel(),
                Select::make('audience')
                    ->label('Audience')
                    ->options([
                        'particulier' => 'Particulier',
                        'professionnel' => 'Professionnel',
                    ])
                    ->required(),
                TextInput::make('subject')
                    ->label('Sujet')
                    ->required(),
                Textarea::make('message')
                    ->label('Message')
                    ->required()
                    ->columnSpanFull(),
                Toggle::make('is_read')
                    ->label('Lu')
                    ->default(false),
            ]);
    }
}
