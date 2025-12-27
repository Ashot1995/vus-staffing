<?php

namespace App\Filament\Resources\EmployersPageContentResource\Pages;

use App\Filament\Resources\EmployersPageContentResource;
use Filament\Actions;
use Filament\Resources\Pages\EditRecord;

class EditEmployersPageContent extends EditRecord
{
    protected static string $resource = EmployersPageContentResource::class;

    protected function getHeaderActions(): array
    {
        return [
            Actions\DeleteAction::make(),
        ];
    }
}
