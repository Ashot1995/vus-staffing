<?php

namespace App\Filament\Resources\ApplicationResource\Pages;

use App\Filament\Resources\ApplicationResource;
use Filament\Actions;
use Filament\Resources\Pages\EditRecord;

class EditApplication extends EditRecord
{
    protected static string $resource = ApplicationResource::class;

    protected function getHeaderActions(): array
    {
        return [
            Actions\DeleteAction::make(),
        ];
    }

    protected function mutateFormDataBeforeSave(array $data): array
    {
        // Calculate start_date based on start_date_option
        if (isset($data['start_date_option'])) {
            switch ($data['start_date_option']) {
                case 'immediately':
                    $data['start_date'] = now()->toDateString();
                    break;
                case 'one_week':
                    $data['start_date'] = now()->addWeek()->toDateString();
                    break;
                case 'one_month':
                    $data['start_date'] = now()->addMonth()->toDateString();
                    break;
                case 'custom':
                    // Keep the custom date if provided
                    if (! isset($data['start_date']) || empty($data['start_date'])) {
                        $data['start_date'] = null;
                    }
                    break;
            }
        }

        return $data;
    }
}
