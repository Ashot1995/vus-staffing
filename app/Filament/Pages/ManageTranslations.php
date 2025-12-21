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

    protected static ?string $navigationGroup = 'Content Management';

    public ?string $selectedSection = 'nav';

    public array $translationData = [];
    
    public array $keyMapping = []; // Maps form field names to translation keys
    
    public array $formData = []; // Stores form field values for validation

    public function mount(): void
    {
        $this->keyMapping = [];
        $this->formData = [];
        $this->loadTranslations();
        $this->initializeFormData();
    }

    protected function initializeFormData(): void
    {
        $sections = $this->getSections();
        
        foreach ($sections as $sectionKey => $sectionName) {
            if (isset($this->translationData[$sectionKey])) {
                $keys = collect($this->translationData[$sectionKey])->keys()->sort()->values();
                
                foreach ($keys as $key) {
                    $fieldKey = str_replace('.', '_', $key);
                    $enFieldName = "{$sectionKey}_{$fieldKey}_en";
                    $svFieldName = "{$sectionKey}_{$fieldKey}_sv";
                    
                    // Initialize form data array for Livewire
                    $this->formData[$enFieldName] = $this->translationData[$sectionKey][$key]['en'] ?? '';
                    $this->formData[$svFieldName] = $this->translationData[$sectionKey][$key]['sv'] ?? '';
                }
            }
        }
    }

    public function loadTranslations(): void
    {
        // Load translations from language files instead of database
        $enTranslations = [];
        $svTranslations = [];
        
        if (file_exists(lang_path('en/messages.php'))) {
            $enTranslations = require lang_path('en/messages.php');
        }
        
        if (file_exists(lang_path('sv/messages.php'))) {
            $svTranslations = require lang_path('sv/messages.php');
        }
        
        // Files use flat dot notation, flatten if nested
        $enFlat = $this->flattenArray($enTranslations);
        $svFlat = $this->flattenArray($svTranslations);
        
        // Get all unique keys from both languages
        $allKeys = array_unique(array_merge(array_keys($enFlat), array_keys($svFlat)));
        
        // Group by section
        $this->translationData = [];
        foreach ($allKeys as $key) {
            $section = explode('.', $key)[0];
            
            if (!isset($this->translationData[$section])) {
                $this->translationData[$section] = [];
            }
            
            $this->translationData[$section][$key] = [
                'en' => $enFlat[$key] ?? '',
                'sv' => $svFlat[$key] ?? '',
            ];
        }
        
        // Ensure all sections from getSections() are present (even if empty)
        $sections = $this->getSections();
        foreach ($sections as $sectionKey => $sectionName) {
            if (!isset($this->translationData[$sectionKey])) {
                $this->translationData[$sectionKey] = [];
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
                    
                    $enText = $values['en'] ?? '';
                    $svText = $values['sv'] ?? '';
                    
                    // Determine if field is empty
                    $enEmpty = empty($enText);
                    $svEmpty = empty($svText);
                    
                    $fields[] = Forms\Components\Section::make($keyLabel)
                        ->description("Translation Key: <code class='text-xs bg-gray-100 dark:bg-gray-800 px-1 py-0.5 rounded'>{$key}</code>")
                        ->schema([
                            // Input fields that show current values and allow editing
                            Forms\Components\Grid::make(2)
                                ->schema([
                                    Forms\Components\Textarea::make("formData.{$enFieldName}")
                                        ->label('🇬🇧 English')
                                        ->rows(6)
                                        ->default($this->formData[$enFieldName] ?? $enText)
                                        ->required()
                                        ->placeholder('Enter English translation...')
                                        ->helperText($enEmpty ? '⚠️ No English translation yet' : 'Current: ' . Str::limit($enText, 100))
                                        ->extraAttributes(['style' => 'min-height: 120px;'])
                                        ->columnSpan(1),
                                    Forms\Components\Textarea::make("formData.{$svFieldName}")
                                        ->label('🇸🇪 Swedish')
                                        ->rows(6)
                                        ->default($this->formData[$svFieldName] ?? $svText)
                                        ->required()
                                        ->placeholder('Enter Swedish translation...')
                                        ->helperText($svEmpty ? '⚠️ No Swedish translation yet' : 'Current: ' . Str::limit($svText, 100))
                                        ->extraAttributes(['style' => 'min-height: 120px;'])
                                        ->columnSpan(1),
                                ]),
                        ])
                        ->collapsible()
                        ->collapsed(false);
                }
            } else {
                // Show empty state for sections with no translations
                $fields[] = Forms\Components\Section::make('No Translations')
                    ->description("This section doesn't have any translations yet.")
                    ->schema([]);
            }

            // Always show the tab, even if empty
            $tabIndex = count($tabs);
            $tabIndexMap[$sectionKey] = $tabIndex;
            $translationCount = isset($this->translationData[$sectionKey]) ? count($this->translationData[$sectionKey]) : 0;
            $tabs[] = Forms\Components\Tabs\Tab::make($sectionName)
                ->badge($translationCount > 0 ? $translationCount : null)
                ->schema($fields);
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
        
        // Process form data and save translations to files instead of database
        // Data will be nested under 'formData' key
        $formData = $data['formData'] ?? [];
        
        // Group translations by locale
        $translationsByLocale = ['en' => [], 'sv' => []];
        
        foreach ($formData as $fieldName => $value) {
            if (isset($this->keyMapping[$fieldName])) {
                $mapping = $this->keyMapping[$fieldName];
                $locale = $mapping['locale'];
                $key = $mapping['key'];
                
                // Store translation by locale and key
                $translationsByLocale[$locale][$key] = $value ?? '';
            }
        }
        
        // Save translations to language files
        foreach (['en', 'sv'] as $locale) {
            $this->saveTranslationsToFile($locale, $translationsByLocale[$locale]);
        }

        // Clear translation cache so changes are immediately visible on the website
        \Illuminate\Support\Facades\Artisan::call('cache:clear');
        \Illuminate\Support\Facades\Artisan::call('config:clear');

        Notification::make()
            ->title('Translations saved successfully')
            ->body('All translation changes have been saved to language files.')
            ->success()
            ->send();

        $this->loadTranslations();
        $this->initializeFormData(); // Reinitialize form data with updated translations
        
        // Reset form to show updated values
        $this->form->fill();
    }

    public function writeAllTranslationsToFiles(): void
    {
        // Get all translations from database
        $allTranslations = Translation::where('group', 'messages')->get();
        
        // Group by locale
        $translationsByLocale = ['en' => [], 'sv' => []];
        
        foreach ($allTranslations as $translation) {
            $translationsByLocale[$translation->locale][$translation->key] = $translation->value;
        }
        
        // Write all translations to files
        foreach (['en', 'sv'] as $locale) {
            $this->saveTranslationsToFile($locale, $translationsByLocale[$locale]);
        }
        
        Notification::make()
            ->title('All translations written to files')
            ->body('All translations from database have been written to language files.')
            ->success()
            ->send();
        
        $this->loadTranslations();
        $this->initializeFormData();
    }

    /**
     * Save translations to language file
     */
    protected function saveTranslationsToFile(string $locale, array $translations): void
    {
        $filePath = lang_path("{$locale}/messages.php");
        
        // Read existing translations
        $existingTranslations = [];
        if (file_exists($filePath)) {
            $existingTranslations = require $filePath;
        }
        
        // Merge new translations with existing ones (new translations override existing)
        // The translations come as flat dot notation, so merge directly
        $mergedTranslations = array_merge($existingTranslations, $translations);
        
        // Generate PHP file content with proper formatting (flat dot notation format)
        $content = "<?php\n\nreturn [\n";
        $content .= $this->arrayToPhpStringFlat($mergedTranslations);
        $content .= "];\n";
        
        // Write to file
        \Illuminate\Support\Facades\File::ensureDirectoryExists(dirname($filePath));
        \Illuminate\Support\Facades\File::put($filePath, $content);
    }

    /**
     * Convert flat array to PHP string (for dot notation format)
     */
    protected function arrayToPhpStringFlat(array $array): string
    {
        $result = '';
        $currentSection = '';
        
        // Sort keys for better organization
        ksort($array);
        
        foreach ($array as $key => $value) {
            // Add section comment
            $parts = explode('.', $key);
            $section = $parts[0];
            
            if ($section !== $currentSection) {
                if ($currentSection !== '') {
                    $result .= "\n";
                }
                $result .= "    // " . ucfirst(str_replace('_', ' ', $section)) . "\n";
                $currentSection = $section;
            }
            
            $keyStr = var_export($key, true);
            $valueStr = var_export($value, true);
            $result .= "    {$keyStr} => {$valueStr},\n";
        }
        
        return $result;
    }

    /**
     * Flatten nested array back to dot notation
     */
    protected function flattenArray(array $array, string $prefix = ''): array
    {
        $result = [];
        foreach ($array as $key => $value) {
            $newKey = $prefix ? "{$prefix}.{$key}" : $key;
            if (is_array($value)) {
                $result = array_merge($result, $this->flattenArray($value, $newKey));
            } else {
                $result[$newKey] = $value;
            }
        }
        return $result;
    }

    /**
     * Convert array to PHP string representation
     */
    protected function arrayToPhpString(array $array, int $indent = 0): string
    {
        $spaces = str_repeat('    ', $indent);
        $result = '';
        
        // Sort keys for better organization
        ksort($array);
        
        foreach ($array as $key => $value) {
            $keyStr = var_export($key, true);
            
            if (is_array($value) && !empty($value)) {
                $result .= "{$spaces}{$keyStr} => [\n";
                $result .= $this->arrayToPhpString($value, $indent + 1);
                $result .= "{$spaces}],\n";
            } else {
                $valueStr = var_export($value, true);
                $result .= "{$spaces}{$keyStr} => {$valueStr},\n";
            }
        }
        
        return $result;
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
            'privacy' => 'Privacy Policy',
            'validation' => 'Validation Messages',
            'auth' => 'Authentication',
            'admin' => 'Admin',
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
