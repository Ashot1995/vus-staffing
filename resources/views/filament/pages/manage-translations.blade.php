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

        <form wire:submit="save" class="translations-form">
            {{ $this->form }}

            <!-- Sticky Save Button -->
            <div class="translations-save-actions sticky bottom-0 left-0 right-0 bg-white dark:bg-gray-800 border-t border-gray-200 dark:border-gray-700 shadow-lg z-50 p-4 mt-6">
                <div class="max-w-7xl mx-auto flex justify-end gap-3">
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
            </div>
        </form>
    </div>

    @push('styles')
    <style>
        .translations-form {
            padding-bottom: 100px;
            position: relative;
        }
        
        .translations-save-actions {
            position: sticky;
            bottom: 0;
            left: 0;
            right: 0;
            background: white;
            border-top: 1px solid rgb(229 231 235);
            box-shadow: 0 -4px 6px -1px rgb(0 0 0 / 0.1), 0 -2px 4px -2px rgb(0 0 0 / 0.1);
            z-index: 50;
            padding: 1rem;
            margin-top: 2rem;
            margin-left: calc(-1 * var(--page-padding-x, 1.5rem));
            margin-right: calc(-1 * var(--page-padding-x, 1.5rem));
            margin-bottom: calc(-1 * var(--page-padding-y, 1.5rem));
        }
        
        .dark .translations-save-actions {
            background: rgb(31 41 55);
            border-top-color: rgb(55 65 81);
        }
        
        .translations-save-actions .max-w-7xl {
            max-width: 100%;
            width: 100%;
        }
        
        @media (max-width: 1024px) {
            .translations-save-actions {
                margin-left: calc(-1 * var(--page-padding-x, 1rem));
                margin-right: calc(-1 * var(--page-padding-x, 1rem));
                margin-bottom: calc(-1 * var(--page-padding-y, 1rem));
            }
        }
    </style>
    @endpush
</x-filament-panels::page>
