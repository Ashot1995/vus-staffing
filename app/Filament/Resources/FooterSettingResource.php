<?php

namespace App\Filament\Resources;

use App\Filament\Resources\FooterSettingResource\Pages;
use App\Models\FooterSetting;
use Filament\Forms;
use Filament\Forms\Form;
use Filament\Resources\Resource;
use Filament\Tables;
use Filament\Tables\Table;

class FooterSettingResource extends Resource
{
    protected static ?string $model = FooterSetting::class;

    protected static ?string $navigationIcon = 'heroicon-o-rectangle-stack';

    protected static ?string $navigationLabel = 'Footer Settings';

    protected static ?string $navigationGroup = 'Content Management';

    protected static ?int $navigationSort = 8;

    public static function form(Form $form): Form
    {
        return $form
            ->schema([
                Forms\Components\Section::make('Brand & Contact Information')
                    ->schema([
                        Forms\Components\TextInput::make('brand_text')
                            ->label('Brand Text')
                            ->required()
                            ->maxLength(255)
                            ->default('V U S')
                            ->helperText('The brand name displayed in the footer')
                            ->columnSpanFull(),
                        
                        Forms\Components\TextInput::make('location_en')
                            ->label('Location (English)')
                            ->required()
                            ->maxLength(255)
                            ->default('Sweden')
                            ->columnSpan(1),
                        
                        Forms\Components\TextInput::make('location_sv')
                            ->label('Location (Swedish)')
                            ->required()
                            ->maxLength(255)
                            ->default('Sverige')
                            ->columnSpan(1),
                        
                        Forms\Components\TextInput::make('email')
                            ->label('Email Address')
                            ->email()
                            ->required()
                            ->maxLength(255)
                            ->default('abdulrazek.mahmoud@vus-bemanning.se')
                            ->helperText('Contact email displayed in footer')
                            ->columnSpanFull(),
                    ])
                    ->columns(2),

                Forms\Components\Section::make('Quick Links')
                    ->schema([
                        Forms\Components\TextInput::make('quick_links_title_en')
                            ->label('Quick Links Title (English)')
                            ->required()
                            ->maxLength(255)
                            ->default('Quick links')
                            ->columnSpan(1),
                        
                        Forms\Components\TextInput::make('quick_links_title_sv')
                            ->label('Quick Links Title (Swedish)')
                            ->required()
                            ->maxLength(255)
                            ->default('Snabblänkar')
                            ->columnSpan(1),
                        
                        Forms\Components\Repeater::make('quick_links')
                            ->label('Quick Links')
                            ->schema([
                                Forms\Components\TextInput::make('label_en')
                                    ->label('Link Label (English)')
                                    ->required()
                                    ->maxLength(255),
                                
                                Forms\Components\TextInput::make('label_sv')
                                    ->label('Link Label (Swedish)')
                                    ->required()
                                    ->maxLength(255),
                                
                                Forms\Components\Select::make('route')
                                    ->label('Route/URL')
                                    ->options([
                                        'for-employers' => 'For Employers',
                                        'contact' => 'Contact',
                                        'about' => 'About Us',
                                        'jobs.index' => 'Jobs',
                                        'blog.index' => 'Blog',
                                        'company-values' => 'Company Values',
                                        'privacy' => 'Privacy Policy',
                                        'home' => 'Home',
                                    ])
                                    ->required(),
                                
                                Forms\Components\TextInput::make('custom_url')
                                    ->label('Custom URL (if route is not selected)')
                                    ->url()
                                    ->maxLength(255)
                                    ->helperText('Leave empty if using route above'),
                            ])
                            ->columns(2)
                            ->defaultItems(4)
                            ->itemLabel(fn (array $state): ?string => ($state['label_en'] ?? 'New Link') . ' → ' . ($state['route'] ?? $state['custom_url'] ?? 'No URL'))
                            ->columnSpanFull()
                            ->helperText('Add, edit, or remove quick links that appear in the footer'),
                    ])
                    ->columns(2),

                Forms\Components\Section::make('Copyright & Legal')
                    ->schema([
                        Forms\Components\Textarea::make('copyright_en')
                            ->label('Copyright Text (English)')
                            ->required()
                            ->rows(2)
                            ->placeholder('© 2025 V U S Entreprenad & Bemanning AB - Org.nr 559540-8963')
                            ->helperText('Use :year to automatically insert current year')
                            ->columnSpan(1),
                        
                        Forms\Components\Textarea::make('copyright_sv')
                            ->label('Copyright Text (Swedish)')
                            ->required()
                            ->rows(2)
                            ->placeholder('© 2025 V U S Entreprenad & Bemanning AB - Org.nr 559540-8963')
                            ->helperText('Use :year to automatically insert current year')
                            ->columnSpan(1),
                    ])
                    ->columns(2),

                Forms\Components\Section::make('Settings')
                    ->schema([
                        Forms\Components\Toggle::make('is_active')
                            ->label('Active')
                            ->default(true)
                            ->helperText('Only active footer settings will be displayed on the website.'),
                    ]),
            ]);
    }

    public static function table(Table $table): Table
    {
        return $table
            ->columns([
                Tables\Columns\TextColumn::make('brand_text')
                    ->label('Brand')
                    ->searchable()
                    ->sortable(),
                
                Tables\Columns\TextColumn::make('email')
                    ->label('Email')
                    ->searchable(),
                
                Tables\Columns\IconColumn::make('is_active')
                    ->label('Active')
                    ->boolean()
                    ->sortable(),
                
                Tables\Columns\TextColumn::make('updated_at')
                    ->label('Last Updated')
                    ->dateTime()
                    ->sortable(),
            ])
            ->filters([
                Tables\Filters\TernaryFilter::make('is_active')
                    ->label('Active')
                    ->placeholder('All settings')
                    ->trueLabel('Active only')
                    ->falseLabel('Inactive only'),
            ])
            ->actions([
                Tables\Actions\EditAction::make(),
                Tables\Actions\DeleteAction::make(),
            ])
            ->bulkActions([
                Tables\Actions\BulkActionGroup::make([
                    Tables\Actions\DeleteBulkAction::make(),
                ]),
            ])
            ->defaultSort('updated_at', 'desc');
    }

    public static function getRelations(): array
    {
        return [
            //
        ];
    }

    public static function getPages(): array
    {
        return [
            'index' => Pages\ListFooterSettings::route('/'),
            'create' => Pages\CreateFooterSetting::route('/create'),
            'edit' => Pages\EditFooterSetting::route('/{record}/edit'),
        ];
    }
}
