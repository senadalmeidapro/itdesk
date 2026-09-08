<?php

namespace App\Filament\Resources\ContactMessages\Schemas;

use App\Models\ContactMessage;
use Filament\Forms\Components\Select;
use Filament\Forms\Components\Textarea;
use Filament\Forms\Components\TextInput;
use Filament\Forms\Components\Toggle;
use Filament\Schemas\Components\Section;
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
                Section::make('Réponses au formulaire de service')
                    ->description('Champs personnalisés renseignés par le visiteur selon le service sélectionné.')
                    ->schema(fn (ContactMessage $record): array => self::serviceAnswers($record))
                    ->visible(fn (ContactMessage $record): bool => filled($record->form_data))
                    ->columns(2)
                    ->columnSpanFull(),
            ]);
    }

    private static function serviceAnswers(ContactMessage $record): array
    {
        $fields = [];

        foreach ($record->formSchema() as $field) {
            $value = $record->form_data[$field['name']] ?? null;
            if ($value === null || $value === '') {
                continue;
            }
            if (($field['type'] ?? null) === 'select') {
                $value = $field['options'][$value] ?? $value;
            }
            $fields[] = TextInput::make("form_data.{$field['name']}")
                ->label($field['label'])
                ->default((string) $value)
                ->disabled()
                ->dehydrated(false);
        }

        return $fields;
    }
}
