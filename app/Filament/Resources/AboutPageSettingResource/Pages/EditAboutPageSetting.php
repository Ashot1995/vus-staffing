<?php

namespace App\Filament\Resources\AboutPageSettingResource\Pages;

use App\Filament\Resources\AboutPageSettingResource;
use Filament\Actions;
use Filament\Resources\Pages\EditRecord;

class EditAboutPageSetting extends EditRecord
{
    protected static string $resource = AboutPageSettingResource::class;

    protected function getHeaderActions(): array
    {
        return [
            Actions\DeleteAction::make(),
        ];
    }
}
