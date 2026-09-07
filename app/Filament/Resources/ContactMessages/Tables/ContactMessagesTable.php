<?php

namespace App\Filament\Resources\ContactMessages\Tables;

use App\Models\Category;
use App\Models\ContactMessage;
use App\Models\User;
use App\Services\LeadConverter;
use Filament\Actions\Action;
use Filament\Actions\BulkActionGroup;
use Filament\Actions\DeleteBulkAction;
use Filament\Actions\EditAction;
use Filament\Forms\Components\Select;
use Filament\Forms\Components\TextInput;
use Filament\Notifications\Notification;
use Filament\Tables\Columns\TextColumn;
use Filament\Tables\Filters\SelectFilter;
use Filament\Tables\Table;
use Illuminate\Support\Str;

class ContactMessagesTable
{
    public static function configure(Table $table): Table
    {
        return $table
            ->defaultSort('created_at', 'desc')
            ->columns([
                TextColumn::make('created_at')
                    ->label('Reçu le')
                    ->dateTime()
                    ->sortable(),
                TextColumn::make('name')
                    ->label('Nom')
                    ->searchable(),
                TextColumn::make('email')
                    ->label('Adresse e-mail')
                    ->searchable(),
                TextColumn::make('phone')
                    ->label('Téléphone')
                    ->searchable(),
                TextColumn::make('service_slug')
                    ->label('Service')
                    ->formatStateUsing(fn (?string $state): string => $state
                        ? (collect(config('public-services.services'))->firstWhere('slug', $state)['name'] ?? $state)
                        : 'Non précisé')
                    ->badge()
                    ->color('gray'),
                TextColumn::make('status')
                    ->label('Statut')
                    ->badge()
                    ->formatStateUsing(fn (string $state): string => ContactMessage::STATUSES[$state] ?? $state)
                    ->color(fn (string $state): ?string => match ($state) {
                        ContactMessage::STATUS_NEW => 'warning',
                        ContactMessage::STATUS_CONTACTED => 'info',
                        ContactMessage::STATUS_CONVERTED => 'success',
                        ContactMessage::STATUS_REJECTED => 'danger',
                        default => 'gray',
                    })
                    ->sortable(),
                TextColumn::make('convertedTicket.id')
                    ->label('Ticket')
                    ->formatStateUsing(fn (?int $state): ?string => $state !== null ? "#{$state}" : null)
                    ->color('success'),
                TextColumn::make('subject')
                    ->label('Sujet')
                    ->searchable(),
                TextColumn::make('is_read')
                    ->label('Lu')
                    ->badge()
                    ->formatStateUsing(fn ($state): string => $state ? 'Lu' : 'Non lu')
                    ->color(fn ($state): string => $state ? 'success' : 'warning'),
            ])
            ->filters([
                SelectFilter::make('status')
                    ->options(ContactMessage::STATUSES),
                SelectFilter::make('service_slug')
                    ->label('Service')
                    ->options(fn (): array => collect(config('public-services.services'))
                        ->pluck('name', 'slug')
                        ->all()),
            ])
            ->recordActions([
                Action::make('contacted')
                    ->label('Marquer contacté')
                    ->icon('heroicon-o-phone')
                    ->color('info')
                    ->visible(fn (ContactMessage $record): bool => $record->status === ContactMessage::STATUS_NEW)
                    ->action(function (ContactMessage $record): void {
                        $record->markContacted();

                        Notification::make()
                            ->success()
                            ->title('Demande marquée « contactée ».')
                            ->send();
                    }),
                Action::make('convert')
                    ->label('Convertir en ticket')
                    ->icon('heroicon-o-arrow-right-circle')
                    ->color('success')
                    ->visible(fn (ContactMessage $record): bool => $record->status !== ContactMessage::STATUS_CONVERTED)
                    ->modalHeading('Convertir en ticket')
                    ->modalDescription('Le ticket sera créé en statut « affecté » : la validation a déjà eu lieu.')
                    ->modalSubmitActionLabel('Créer le ticket')
                    ->schema([
                        Select::make('client_id')
                            ->label('Client')
                            ->options(fn (): array => User::role('requester')
                                ->orderBy('name')
                                ->pluck('name', 'id')
                                ->all())
                            ->searchable()
                            ->required()
                            ->placeholder('Choisir un client, ou en créer un')
                            ->createOptionForm([
                                TextInput::make('name')
                                    ->label('Nom complet')
                                    ->required(),
                                TextInput::make('email')
                                    ->label('Adresse e-mail')
                                    ->email()
                                    ->required()
                                    ->unique('users', 'email'),
                            ])
                            ->createOptionUsing(function (array $data): int {
                                $user = User::create([
                                    'name' => $data['name'],
                                    'email' => $data['email'],
                                    'password' => Str::password(16),
                                ]);
                                $user->assignRole('requester');

                                return $user->id;
                            }),
                        Select::make('category_id')
                            ->label('Catégorie')
                            ->options(fn (): array => Category::orderBy('name')->pluck('name', 'id')->all())
                            ->searchable()
                            ->preload(),
                        Select::make('priority')
                            ->label('Priorité')
                            ->options([
                                'low' => 'Basse',
                                'medium' => 'Moyenne',
                                'high' => 'Haute',
                                'critical' => 'Critique',
                            ])
                            ->default('medium')
                            ->required(),
                        Select::make('assigned_agent_id')
                            ->label('Technicien')
                            ->options(fn (): array => User::role(['agent', 'network_tech', 'admin'])
                                ->orderBy('name')
                                ->pluck('name', 'id')
                                ->all())
                            ->searchable()
                            ->preload(),
                    ])
                    ->action(function (ContactMessage $record, array $data): void {
                        $ticket = app(LeadConverter::class)->convert($record, $data);

                        Notification::make()
                            ->success()
                            ->title('Ticket #'.$ticket->id.' créé.')
                            ->body('La demande est passée à la file d\'intervention.')
                            ->send();
                    }),
                Action::make('reject')
                    ->label('Rejeter')
                    ->icon('heroicon-o-x-circle')
                    ->color('danger')
                    ->requiresConfirmation()
                    ->visible(fn (ContactMessage $record): bool => ! in_array($record->status, [
                        ContactMessage::STATUS_CONVERTED,
                        ContactMessage::STATUS_REJECTED,
                    ], true))
                    ->action(function (ContactMessage $record): void {
                        $record->markRejected();

                        Notification::make()
                            ->success()
                            ->title('Demande rejetée.')
                            ->send();
                    }),
                EditAction::make(),
            ])
            ->toolbarActions([
                BulkActionGroup::make([
                    DeleteBulkAction::make(),
                ]),
            ]);
    }
}
