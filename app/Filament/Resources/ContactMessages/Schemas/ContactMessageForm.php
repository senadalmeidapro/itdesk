<?php

namespace App\Filament\Resources\ContactMessages\Schemas;

use App\Models\ContactMessage;
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
                        'entreprise' => 'Entreprise',
                    ])
                    ->required(),
                Select::make('service_slug')
                    ->label('Service concerné')
                    ->options(fn (): array => collect(config('public-services.services'))
                        ->pluck('name', 'slug')
                        ->all())
                    ->searchable()
                    ->placeholder('Non précisé'),
                Select::make('status')
                    ->label('Statut')
                    ->options(ContactMessage::STATUSES)
                    ->default(ContactMessage::STATUS_NEW)
                    ->required()
                    ->columnSpanFull(),
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
