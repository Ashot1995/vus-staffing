<?php

namespace App\Filament\Resources\CandidateInformationResource\Pages;

use App\Filament\Resources\CandidateInformationResource;
use Filament\Actions;
use Filament\Resources\Pages\ListRecords;

class ListCandidateInformations extends ListRecords
{
    protected static string $resource = CandidateInformationResource::class;

    protected function getHeaderActions(): array
    {
        return [
            Actions\CreateAction::make(),
        ];
    }
}
