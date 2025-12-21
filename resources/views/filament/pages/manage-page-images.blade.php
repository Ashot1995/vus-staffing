<x-filament-panels::page>
    <div class="space-y-6">
        <div class="rounded-lg bg-primary-50 dark:bg-primary-900/10 p-4">
            <div class="flex items-start">
                <div class="flex-shrink-0">
                    <x-filament::icon
                        icon="heroicon-o-information-circle"
                        class="h-5 w-5 text-primary-600 dark:text-primary-400"
                    />
                </div>
                <div class="ml-3 flex-1">
                    <h3 class="text-sm font-medium text-primary-800 dark:text-primary-200">
                        Manage All Page Images
                    </h3>
                    <div class="mt-2 text-sm text-primary-700 dark:text-primary-300">
                        <p><strong>Upload and manage images for all website pages.</strong> Images are organized by page and section. You can upload images, edit them with the built-in image editor, and add alt text for accessibility and SEO.</p>
                        <p class="mt-1"><strong>Available Pages:</strong> About Us, Home, For Employers, Contact, Jobs, Spontaneous Application, Privacy Policy</p>
                        <p class="mt-1"><strong>Note:</strong> Maximum file size is 5MB. Supported formats: JPG, PNG, GIF, WebP.</p>
                    </div>
                </div>
            </div>
        </div>

        <form wire:submit="save">
            {{ $this->form }}

            <div class="mt-6 flex justify-end gap-3">
                <x-filament::button
                    type="button"
                    color="gray"
                    wire:click="loadImages"
                >
                    Reload
                </x-filament::button>
                <x-filament::button type="submit">
                    Save All Images
                </x-filament::button>
            </div>
        </form>
    </div>
</x-filament-panels::page>
