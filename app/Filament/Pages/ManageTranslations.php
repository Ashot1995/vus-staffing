<?php

namespace App\Filament\Pages;

use App\Models\Translation;
use Filament\Forms;
use Filament\Forms\Concerns\InteractsWithForms;
use Filament\Forms\Contracts\HasForms;
use Filament\Forms\Form;
use Filament\Notifications\Notification;
use Filament\Pages\Page;
use Illuminate\Support\Str;
use Livewire\Attributes\On;

class ManageTranslations extends Page implements HasForms
{
    use InteractsWithForms;

    protected static ?string $navigationIcon = 'heroicon-o-language';

    protected static string $view = 'filament.pages.manage-translations';

    protected static ?string $navigationLabel = 'Manage Translations';

    protected static ?int $navigationSort = 4;

    public ?string $selectedSection = 'nav';

    public array $translationData = [];
    
    public array $keyMapping = []; // Maps form field names to translation keys

    public function mount(): void
    {
        $this->keyMapping = [];
        $this->loadTranslations();
    }

    public function loadTranslations(): void
    {
        $allTranslations = Translation::where('group', 'messages')
            ->get()
            ->groupBy(function ($translation) {
                return explode('.', $translation->key)[0];
            });

        $this->translationData = [];
        foreach ($allTranslations as $section => $items) {
            $keys = $items->pluck('key')->unique();
            foreach ($keys as $key) {
                $en = $items->where('key', $key)->where('locale', 'en')->first();
                $sv = $items->where('key', $key)->where('locale', 'sv')->first();

                if (!isset($this->translationData[$section])) {
                    $this->translationData[$section] = [];
                }

                $this->translationData[$section][$key] = [
                    'en' => $en ? $en->value : '',
                    'sv' => $sv ? $sv->value : '',
                ];
            }
        }
    }

    public function form(Form $form): Form
    {
        $sections = $this->getSections();
        $tabs = [];
        $tabIndexMap = []; // Map section keys to tab indices

        foreach ($sections as $sectionKey => $sectionName) {
            $fields = [];
            
            if (isset($this->translationData[$sectionKey])) {
                // Sort keys for better organization
                $keys = collect($this->translationData[$sectionKey])->keys()->sort()->values();
                
                foreach ($keys as $key) {
                    $values = $this->translationData[$sectionKey][$key];
                    $keyLabel = $this->getKeyLabel($key);
                    $fieldKey = str_replace('.', '_', $key); // Replace dots with underscores for form field names
                    $enFieldName = "{$sectionKey}_{$fieldKey}_en";
                    $svFieldName = "{$sectionKey}_{$fieldKey}_sv";
                    
                    // Store mapping for save operation
                    $this->keyMapping[$enFieldName] = ['key' => $key, 'locale' => 'en'];
                    $this->keyMapping[$svFieldName] = ['key' => $key, 'locale' => 'sv'];
                    
                    $enText = $values['en'] ?? '(empty)';
                    $svText = $values['sv'] ?? '(empty)';
                    
                    $fields[] = Forms\Components\Section::make($keyLabel)
                        ->description("Key: <code class='text-xs bg-gray-100 dark:bg-gray-800 px-1 py-0.5 rounded'>{$key}</code>")
                        ->schema([
                            // Preview section showing current translations
                            Forms\Components\ViewField::make("preview_{$sectionKey}_{$fieldKey}")
                                ->view('filament.components.translation-preview')
                                ->viewData([
                                    'enText' => $enText,
                                    'svText' => $svText,
                                ])
                                ->columnSpanFull(),
                            
                            // Input fields for editing
                            Forms\Components\Grid::make(2)
                                ->schema([
                                    Forms\Components\Textarea::make($enFieldName)
                                        ->label('🇬🇧 Edit English')
                                        ->rows(4)
                                        ->default($values['en'] ?? '')
                                        ->required()
                                        ->placeholder('Enter English translation...'),
                                    Forms\Components\Textarea::make($svFieldName)
                                        ->label('🇸🇪 Edit Swedish')
                                        ->rows(4)
                                        ->default($values['sv'] ?? '')
                                        ->required()
                                        ->placeholder('Enter Swedish translation...'),
                                ]),
                        ])
                        ->collapsible()
                        ->collapsed(false);
                }
            }

            if (!empty($fields)) {
                $tabIndex = count($tabs);
                $tabIndexMap[$sectionKey] = $tabIndex;
                $tabs[] = Forms\Components\Tabs\Tab::make($sectionName)
                    ->badge(count($fields))
                    ->schema($fields);
            }
        }

        // Find the index of the selected section
        $activeTabIndex = $tabIndexMap[$this->selectedSection] ?? 0;

        return $form
            ->schema([
                Forms\Components\Tabs::make('TranslationSections')
                    ->tabs($tabs)
                    ->activeTab($activeTabIndex)
                    ->persistTabInQueryString(),
            ]);
    }

    public function save(): void
    {
        $data = $this->form->getState();
        
        // Process form data and save translations using key mapping
        foreach ($data as $fieldName => $value) {
            if (isset($this->keyMapping[$fieldName])) {
                $mapping = $this->keyMapping[$fieldName];
                
                Translation::updateOrCreate(
                    [
                        'key' => $mapping['key'],
                        'locale' => $mapping['locale'],
                        'group' => 'messages',
                    ],
                    [
                        'value' => $value ?? '',
                    ]
                );
            }
        }

        Notification::make()
            ->title('Translations saved successfully')
            ->body('All translation changes have been saved to the database.')
            ->success()
            ->send();

        $this->loadTranslations();
        $this->mount(); // Reload form with updated data
    }

    protected function getSections(): array
    {
        return [
            'nav' => 'Navigation',
            'home' => 'Home Page',
            'about' => 'About Page',
            'employers' => 'For Employers',
            'jobs' => 'Jobs Page',
            'contact' => 'Contact Page',
            'footer' => 'Footer',
            'apply' => 'Apply Page',
            'spontaneous' => 'Spontaneous Application',
            'dashboard' => 'Dashboard',
            'profile' => 'Profile',
            'cookie' => 'Cookie Banner',
            'newsletter' => 'Newsletter',
            'common' => 'Common',
        ];
    }

    protected function getKeyLabel(string $key): string
    {
        $parts = explode('.', $key);
        $label = Str::title(str_replace('_', ' ', end($parts)));
        
        if (count($parts) > 2) {
            $context = Str::title(str_replace('_', ' ', $parts[count($parts) - 2]));
            return "{$context} - {$label}";
        }
        
        return $label;
    }
}
