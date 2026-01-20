<?php

namespace App\Filament\Resources\PrivacyPolicyResource\Pages;

use App\Filament\Resources\PrivacyPolicyResource;
use Filament\Actions;
use Filament\Resources\Pages\ListRecords;
use Illuminate\Database\Eloquent\Model;

class ListPrivacyPolicies extends ListRecords
{
    protected static string $resource = PrivacyPolicyResource::class;

    protected function getHeaderActions(): array
    {
        return [
            Actions\CreateAction::make()
                ->visible(fn () => \App\Models\PrivacyPolicy::count() === 0),
        ];
    }

    public function mount(): void
    {
        parent::mount();
        
        // If there's already a privacy policy, redirect to edit it
        $privacyPolicy = \App\Models\PrivacyPolicy::first();
        if ($privacyPolicy) {
            redirect(static::getResource()::getUrl('edit', ['record' => $privacyPolicy]));
        }
    }
}
