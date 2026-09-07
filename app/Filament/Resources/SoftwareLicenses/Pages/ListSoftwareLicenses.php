<?php

namespace App\Filament\Resources\SoftwareLicenses\Pages;

use App\Filament\Resources\SoftwareLicenses\SoftwareLicenseResource;
use Filament\Actions\CreateAction;
use Filament\Resources\Pages\ListRecords;

class ListSoftwareLicenses extends ListRecords
{
    protected static string $resource = SoftwareLicenseResource::class;

    protected function getHeaderActions(): array
    {
        return [
            CreateAction::make(),
        ];
    }
}
