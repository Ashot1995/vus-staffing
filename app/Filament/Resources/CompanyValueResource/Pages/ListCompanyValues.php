<?php

namespace App\Filament\Resources\CompanyValueResource\Pages;

use App\Filament\Resources\CompanyValueResource;
use Filament\Actions;
use Filament\Resources\Pages\ListRecords;

class ListCompanyValues extends ListRecords
{
    protected static string $resource = CompanyValueResource::class;

    protected function getHeaderActions(): array
    {
        return [
            Actions\CreateAction::make(),
        ];
    }
}
