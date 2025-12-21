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
                        Manage All Page Translations
                    </h3>
                    <div class="mt-2 text-sm text-primary-700 dark:text-primary-300">
                        <p>Edit all text content for both English and Swedish languages. All translations from your language files are automatically loaded and organized by page/section.</p>
                        <p class="mt-1"><strong>Note:</strong> Changes are saved directly to language files and will be visible immediately after saving.</p>
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
                    wire:click="loadTranslations"
                >
                    Reload Translations
                </x-filament::button>
                <x-filament::button type="submit">
                    Save All Translations
                </x-filament::button>
            </div>
        </form>
    </div>
</x-filament-panels::page>
