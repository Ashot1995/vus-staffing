<?php

namespace App\Filament\Resources\PartnerResource\Pages;

use App\Filament\Resources\PartnerResource;
use Filament\Actions;
use Filament\Resources\Pages\CreateRecord;

class CreatePartner extends CreateRecord
{
    protected static string $resource = PartnerResource::class;

    protected function mutateFormDataBeforeCreate(array $data): array
    {
        // Ensure logo is null if empty string
        if (isset($data['logo']) && empty($data['logo'])) {
            $data['logo'] = null;
        }
        
        return $data;
    }
}
