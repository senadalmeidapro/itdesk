<?php

namespace App\Filament\Resources\SoftwareLicenses;

use App\Filament\Resources\SoftwareLicenses\Pages\CreateSoftwareLicense;
use App\Filament\Resources\SoftwareLicenses\Pages\EditSoftwareLicense;
use App\Filament\Resources\SoftwareLicenses\Pages\ListSoftwareLicenses;
use App\Filament\Resources\SoftwareLicenses\Schemas\SoftwareLicenseForm;
use App\Filament\Resources\SoftwareLicenses\Tables\SoftwareLicensesTable;
use App\Models\SoftwareLicense;
use BackedEnum;
use Filament\Resources\Resource;
use Filament\Schemas\Schema;
use Filament\Tables\Table;

class SoftwareLicenseResource extends Resource
{
    protected static ?string $model = SoftwareLicense::class;

    protected static string|BackedEnum|null $navigationIcon = 'heroicon-o-key';

    public static function getNavigationGroup(): ?string
    {
        return 'Pilotage';
    }

    public static function getNavigationSort(): ?int
    {
        return 3;
    }

    public static function getModelLabel(): string
    {
        return 'Licence logicielle';
    }

    public static function getPluralModelLabel(): string
    {
        return 'Licences logicielles';
    }

    public static function form(Schema $schema): Schema
    {
        return SoftwareLicenseForm::configure($schema);
    }

    public static function table(Table $table): Table
    {
        return SoftwareLicensesTable::configure($table);
    }

    public static function getRelations(): array
    {
        return [
            //
        ];
    }

    public static function getPages(): array
    {
        return [
            'index' => ListSoftwareLicenses::route('/'),
            'create' => CreateSoftwareLicense::route('/create'),
            'edit' => EditSoftwareLicense::route('/{record}/edit'),
        ];
    }
}
