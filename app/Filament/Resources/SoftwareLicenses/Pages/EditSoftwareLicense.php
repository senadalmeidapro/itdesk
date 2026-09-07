<?php

namespace App\Filament\Resources\SoftwareLicenses\Pages;

use App\Filament\Resources\SoftwareLicenses\SoftwareLicenseResource;
use Filament\Actions\DeleteAction;
use Filament\Resources\Pages\EditRecord;

class EditSoftwareLicense extends EditRecord
{
    protected static string $resource = SoftwareLicenseResource::class;

    protected function getHeaderActions(): array
    {
        return [
            DeleteAction::make(),
        ];
    }
}
