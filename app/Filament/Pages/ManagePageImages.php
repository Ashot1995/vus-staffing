<?php

namespace App\Filament\Pages;

use App\Models\PageImage;
use Filament\Forms;
use Filament\Forms\Concerns\InteractsWithForms;
use Filament\Forms\Contracts\HasForms;
use Filament\Forms\Form;
use Filament\Notifications\Notification;
use Filament\Pages\Page;
use Illuminate\Support\Facades\Storage;

class ManagePageImages extends Page implements HasForms
{
    use InteractsWithForms;

    protected static ?string $navigationIcon = 'heroicon-o-photo';

    protected static string $view = 'filament.pages.manage-page-images';

    protected static ?string $navigationLabel = 'Page Images';

    protected static ?int $navigationSort = 5;

    protected static ?string $navigationGroup = 'Content Management';

    public array $formData = [];

    public function mount(): void
    {
        $this->loadImages();
    }

    protected function loadImages(): void
    {
        $imageSections = $this->getImageSections();
        
        foreach ($imageSections as $page => $sections) {
            foreach ($sections as $section => $config) {
                $image = PageImage::getImage($page, $section);
                $fieldName = "{$page}_{$section}";
                $this->formData[$fieldName] = $image ? $image->image_path : null;
                $this->formData["{$fieldName}_alt"] = $image ? $image->alt_text : '';
            }
        }
    }

    public function form(Form $form): Form
    {
        $sections = [];
        $imageSections = $this->getImageSections();

        foreach ($imageSections as $page => $pageSections) {
            $pageFields = [];
            
            foreach ($pageSections as $section => $config) {
                $fieldName = "{$page}_{$section}";
                $image = PageImage::getImage($page, $section);
                
                $pageFields[] = Forms\Components\Section::make($config['label'])
                    ->description($config['description'] ?? '')
                    ->schema([
                        Forms\Components\FileUpload::make($fieldName)
                            ->label(__('messages.admin.page_images.upload'))
                            ->image()
                            ->acceptedFileTypes(['image/*'])
                            ->directory('page-images')
                            ->visibility('public')
                            ->maxSize(5120) // 5 MB
                            ->imageEditor()
                            ->imageEditorAspectRatios([
                                null,
                                '16:9',
                                '4:3',
                                '1:1',
                            ])
                            ->helperText($config['helper'] ?? '')
                            ->default($image ? $image->image_path : null),
                        
                        Forms\Components\TextInput::make("{$fieldName}_alt")
                            ->label(__('messages.admin.page_images.alt_text'))
                            ->default($image ? $image->alt_text : '')
                            ->maxLength(255),
                    ])
                    ->collapsible();
            }

            $sections[] = Forms\Components\Tabs\Tab::make(ucfirst($page) . ' Page')
                ->badge(count($pageFields))
                ->schema($pageFields);
        }

        return $form
            ->schema([
                Forms\Components\Tabs::make('PageImages')
                    ->tabs($sections)
                    ->persistTabInQueryString(),
            ])
            ->statePath('formData');
    }

    public function save(): void
    {
        $data = $this->form->getState();
        $imageSections = $this->getImageSections();

        foreach ($imageSections as $page => $pageSections) {
            foreach ($pageSections as $section => $config) {
                $fieldName = "{$page}_{$section}";
                $imagePath = $data[$fieldName] ?? null;
                $altText = $data["{$fieldName}_alt"] ?? null;

                $existingImage = PageImage::getImage($page, $section);

                if ($imagePath) {
                    // Delete old image if it exists and is different
                    if ($existingImage && $existingImage->image_path !== $imagePath) {
                        if (Storage::disk('public')->exists($existingImage->image_path)) {
                            Storage::disk('public')->delete($existingImage->image_path);
                        }
                    }

                    PageImage::updateOrCreate(
                        [
                            'page' => $page,
                            'section' => $section,
                        ],
                        [
                            'image_path' => $imagePath,
                            'alt_text' => $altText,
                            'description' => $config['description'] ?? null,
                        ]
                    );
                } elseif ($existingImage && !$imagePath) {
                    // If image was removed, delete the record
                    if (Storage::disk('public')->exists($existingImage->image_path)) {
                        Storage::disk('public')->delete($existingImage->image_path);
                    }
                    $existingImage->delete();
                }
            }
        }

        Notification::make()
            ->title('Images saved successfully')
            ->body('All page images have been saved.')
            ->success()
            ->send();

        $this->loadImages();
        $this->form->fill($this->formData);
    }

    protected function getImageSections(): array
    {
        return [
            'about' => [
                'main_image' => [
                    'label' => 'Main Image (Below Text)',
                    'description' => 'Large image displayed below the main text content',
                    'helper' => 'Recommended size: 1200x500px or larger',
                ],
                'team_member_1' => [
                    'label' => 'Team Member 1 - Abdulrazek Mahmoud',
                    'description' => 'Portrait image for first team member',
                    'helper' => 'Recommended size: 400x500px (portrait)',
                ],
                'team_member_2' => [
                    'label' => 'Team Member 2 - Hrayr Hovhannisyan',
                    'description' => 'Portrait image for second team member',
                    'helper' => 'Recommended size: 400x500px (portrait)',
                ],
                'team_member_3' => [
                    'label' => 'Team Member 3 - Abdulhamid Wadi',
                    'description' => 'Portrait image for third team member',
                    'helper' => 'Recommended size: 400x500px (portrait)',
                ],
            ],
            'home' => [
                'hero_image' => [
                    'label' => 'Hero Background Image/Video',
                    'description' => 'Background for hero section',
                    'helper' => 'Recommended size: 1920x1080px',
                ],
            ],
        ];
    }
}
