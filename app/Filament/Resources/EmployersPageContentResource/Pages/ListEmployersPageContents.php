<?php

namespace App\Filament\Resources\EmployersPageContentResource\Pages;

use App\Filament\Resources\EmployersPageContentResource;
use Filament\Actions;
use Filament\Resources\Pages\ListRecords;

class ListEmployersPageContents extends ListRecords
{
    protected static string $resource = EmployersPageContentResource::class;

    protected function getHeaderActions(): array
    {
        return [
            Actions\CreateAction::make(),
        ];
    }
}
