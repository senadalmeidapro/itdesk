<?php

namespace App\Filament\Resources\Assets\Schemas;

use Filament\Forms\Components\DatePicker;
use Filament\Forms\Components\Select;
use Filament\Forms\Components\Textarea;
use Filament\Forms\Components\TextInput;
use Filament\Schemas\Schema;

class AssetForm
{
    public static function configure(Schema $schema): Schema
    {
        return $schema
            ->columns(2)
            ->components([
                TextInput::make('asset_tag')
                    ->label("Tag d'inventaire")
                    ->required(),
                TextInput::make('serial_number')
                    ->label('Numéro de série'),
                TextInput::make('name')
                    ->label('Nom')
                    ->required()
                    ->columnSpan(2),
                Select::make('type')
                    ->label('Type')
                    ->options([
                        'laptop' => 'Portable',
                        'desktop' => 'Bureau',
                        'cpu' => 'Unité centrale',
                        'monitor' => 'Écran',
                        'hard_disk' => 'Disque dur',
                        'keyboard' => 'Clavier',
                        'mouse' => 'Souris',
                        'printer' => 'Imprimante',
                        'switch' => 'Switch',
                        'router' => 'Routeur',
                        'camera' => 'Caméra',
                        'other' => 'Autre',
                    ])
                    ->required(),
                Select::make('status')
                    ->label('Statut')
                    ->options([
                        'in_stock' => 'En stock',
                        'in_use' => 'En usage',
                        'repair' => 'En réparation',
                        'retired' => 'Retiré',
                    ])
                    ->required()
                    ->default('in_stock'),
                TextInput::make('manufacturer')
                    ->label('Fabricant'),
                TextInput::make('model')
                    ->label('Modèle'),
                Textarea::make('specifications')
                    ->label('Spécifications')
                    ->columnSpan(2),
                TextInput::make('ip_address')
                    ->label('Adresse IP'),
                TextInput::make('mac_address')
                    ->label('Adresse MAC'),
                Select::make('assigned_user_id')
                    ->label('Assigné à')
                    ->relationship('assignedUser', 'name')
                    ->searchable(),
                Select::make('category_id')
                    ->label('Catégorie')
                    ->relationship('category', 'name'),
                TextInput::make('supplier')
                    ->label('Fournisseur'),
                DatePicker::make('purchase_date')
                    ->label("Date d'achat"),
                DatePicker::make('warranty_expires_at')
                    ->label('Fin de garantie'),
                TextInput::make('location')
                    ->label('Emplacement'),
                TextInput::make('qr_code')
                    ->label('Code QR'),
                Textarea::make('notes')
                    ->label('Notes')
                    ->columnSpan(2),
            ]);
    }
}
