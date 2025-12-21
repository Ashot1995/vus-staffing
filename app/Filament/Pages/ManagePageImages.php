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

    public function loadImages(): void
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
        
        $this->form->fill($this->formData);
        
        Notification::make()
            ->title('Images reloaded')
            ->body('All images have been reloaded from the database.')
            ->success()
            ->send();
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
                            ->default($image ? $image->image_path : null)
                            ->imagePreviewHeight('250')
                            ->loadingIndicatorPosition('left')
                            ->panelAspectRatio('2:1')
                            ->panelLayout('integrated')
                            ->removeUploadedFileButtonPosition('right')
                            ->uploadButtonPosition('left')
                            ->uploadProgressIndicatorPosition('left'),
                        
                        Forms\Components\TextInput::make("{$fieldName}_alt")
                            ->label(__('messages.admin.page_images.alt_text'))
                            ->default($image ? $image->alt_text : '')
                            ->maxLength(255)
                            ->helperText('Alt text for accessibility and SEO'),
                    ])
                    ->collapsible()
                    ->collapsed(false);
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
                    'description' => 'Large image displayed below the main text content on the About Us page',
                    'helper' => 'Recommended size: 1200x500px or larger. This image appears below the welcome text.',
                ],
                'team_member_1' => [
                    'label' => 'Team Member 1 - Abdulrazek Mahmoud',
                    'description' => 'Portrait image for Abdulrazek Mahmoud (Founder & Chairman of the Board)',
                    'helper' => 'Recommended size: 400x500px (portrait orientation). Square images work best.',
                ],
                'team_member_2' => [
                    'label' => 'Team Member 2 - Hrayr Hovhannisyan',
                    'description' => 'Portrait image for Hrayr Hovhannisyan (Founder & Board Member)',
                    'helper' => 'Recommended size: 400x500px (portrait orientation). Square images work best.',
                ],
                'team_member_3' => [
                    'label' => 'Team Member 3 - Abdulhamid Wadi',
                    'description' => 'Portrait image for Abdulhamid Wadi (Founder & Board Member)',
                    'helper' => 'Recommended size: 400x500px (portrait orientation). Square images work best.',
                ],
            ],
            'home' => [
                'hero_image' => [
                    'label' => 'Hero Background Image (Optional)',
                    'description' => 'Background image for hero section (currently using video, but you can add a fallback image)',
                    'helper' => 'Recommended size: 1920x1080px. Note: Currently a video is used in the hero section, this would be a fallback.',
                ],
                'section_2_image' => [
                    'label' => 'Section 2 Background Image (Optional)',
                    'description' => 'Background image for the highlight section with three cards',
                    'helper' => 'Recommended size: 1920x600px. Optional background image.',
                ],
                'section_3_image' => [
                    'label' => 'Section 3 Background Image (Optional)',
                    'description' => 'Background image for "Why Choose VUS" section',
                    'helper' => 'Recommended size: 1920x600px. Optional background image.',
                ],
            ],
            'employers' => [
                'header_image' => [
                    'label' => 'Header Background Image',
                    'description' => 'Background image for the header section on For Employers page',
                    'helper' => 'Recommended size: 1920x600px. This appears behind the page title.',
                ],
                'services_image' => [
                    'label' => 'Services Section Image (Optional)',
                    'description' => 'Optional image for the services section',
                    'helper' => 'Recommended size: 1200x500px. Optional decorative image.',
                ],
            ],
            'contact' => [
                'header_image' => [
                    'label' => 'Header Background Image',
                    'description' => 'Background image for the header section on Contact page',
                    'helper' => 'Recommended size: 1920x600px. This appears behind the page title.',
                ],
                'map_image' => [
                    'label' => 'Map Image (Optional)',
                    'description' => 'Optional map or location image for contact page',
                    'helper' => 'Recommended size: 800x600px. Optional map image.',
                ],
            ],
            'jobs' => [
                'header_image' => [
                    'label' => 'Header Background Image',
                    'description' => 'Background image for the header section on Jobs listing page',
                    'helper' => 'Recommended size: 1920x600px. This appears behind the page title.',
                ],
            ],
            'spontaneous' => [
                'header_image' => [
                    'label' => 'Header Background Image',
                    'description' => 'Background image for the header section on Spontaneous Application page',
                    'helper' => 'Recommended size: 1920x600px. This appears behind the page title.',
                ],
            ],
            'privacy' => [
                'header_image' => [
                    'label' => 'Header Background Image',
                    'description' => 'Background image for the header section on Privacy Policy page',
                    'helper' => 'Recommended size: 1920x600px. This appears behind the page title.',
                ],
            ],
        ];
    }
}
