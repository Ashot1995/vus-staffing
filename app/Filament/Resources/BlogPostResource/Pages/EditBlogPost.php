<?php

namespace App\Filament\Resources\BlogPostResource\Pages;

use App\Filament\Resources\BlogPostResource;
use Filament\Actions;
use Filament\Resources\Pages\EditRecord;

class EditBlogPost extends EditRecord
{
    protected static string $resource = BlogPostResource::class;

    protected function getHeaderActions(): array
    {
        return [
            Actions\DeleteAction::make(),
        ];
    }

    protected function mutateFormDataBeforeSave(array $data): array
    {
        // Set published_at if not set and post is being published
        if (isset($data['is_published']) && $data['is_published'] && empty($data['published_at'])) {
            $data['published_at'] = now();
        }
        
        // Ensure featured_image is null if empty
        if (isset($data['featured_image']) && empty($data['featured_image'])) {
            $data['featured_image'] = null;
        }
        
        return $data;
    }
}
