<x-filament-panels::page>
    <div class="space-y-6">
        <form wire:submit="save">
            {{ $this->form }}

            <div class="mt-6 flex justify-end gap-3">
                <x-filament::button
                    type="button"
                    color="gray"
                    wire:click="loadTranslations"
                >
                    Reload
                </x-filament::button>
                <x-filament::button type="submit">
                    Save All Translations
                </x-filament::button>
            </div>
        </form>
    </div>
</x-filament-panels::page>
