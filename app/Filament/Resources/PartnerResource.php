<?php

namespace App\Filament\Resources;

use App\Filament\Resources\PartnerResource\Pages;
use App\Filament\Resources\PartnerResource\RelationManagers;
use App\Models\Partner;
use Filament\Forms;
use Filament\Forms\Form;
use Filament\Resources\Resource;
use Filament\Tables;
use Filament\Tables\Table;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\SoftDeletingScope;

class PartnerResource extends Resource
{
    protected static ?string $model = Partner::class;

    protected static ?string $navigationIcon = 'heroicon-o-user-group';

    protected static ?string $navigationLabel = 'Partners';

    protected static ?string $navigationGroup = 'Content Management';

    protected static ?int $navigationSort = 6;

    public static function form(Form $form): Form
    {
        return $form
            ->schema([
                Forms\Components\Section::make('Basic Information')
                    ->schema([
                        Forms\Components\TextInput::make('name')
                            ->label(__('messages.admin.partner.name'))
                            ->required()
                            ->maxLength(255)
                            ->columnSpanFull(),
                        
                        Forms\Components\Textarea::make('description')
                            ->label(__('messages.admin.partner.description'))
                            ->rows(3)
                            ->columnSpanFull(),
                        
                        Forms\Components\TextInput::make('website_url')
                            ->label(__('messages.admin.partner.website'))
                            ->url()
                            ->maxLength(255)
                            ->placeholder('https://example.com')
                            ->columnSpanFull(),
                    ])
                    ->columns(2),

                Forms\Components\Section::make('Logo')
                    ->schema([
                        Forms\Components\FileUpload::make('logo')
                            ->label(__('messages.admin.partner.logo'))
                            ->image()
                            ->directory('partners')
                            ->disk('public')
                            ->visibility('public')
                            ->maxSize(2048) // 2 MB
                            ->imageEditor()
                            ->imageEditorAspectRatios([
                                null,
                                '16:9',
                                '4:3',
                                '1:1',
                            ])
                            ->helperText('Upload partner logo. Recommended size: 300x200px or larger.')
                            ->columnSpanFull()
                            ->acceptedFileTypes(['image/*']),
                    ]),

                Forms\Components\Section::make('Settings')
                    ->schema([
                        Forms\Components\Toggle::make('is_active')
                            ->label(__('messages.admin.partner.active'))
                            ->default(true)
                            ->helperText('Only active partners will be displayed on the website.'),
                        
                        Forms\Components\TextInput::make('sort_order')
                            ->label(__('messages.admin.partner.sort_order'))
                            ->numeric()
                            ->default(0)
                            ->helperText('Lower numbers appear first.'),
                    ])
                    ->columns(2),
            ]);
    }

    public static function table(Table $table): Table
    {
        return $table
            ->columns([
                Tables\Columns\ImageColumn::make('logo')
                    ->label(__('messages.admin.partner.logo'))
                    ->disk('public')
                    ->circular()
                    ->size(50),
                
                Tables\Columns\TextColumn::make('name')
                    ->label(__('messages.admin.partner.name'))
                    ->searchable()
                    ->sortable(),
                
                Tables\Columns\TextColumn::make('website_url')
                    ->label(__('messages.admin.partner.website'))
                    ->url(fn ($record) => $record->website_url)
                    ->openUrlInNewTab()
                    ->limit(30)
                    ->searchable(),
                
                Tables\Columns\IconColumn::make('is_active')
                    ->label(__('messages.admin.partner.active'))
                    ->boolean()
                    ->sortable(),
                
                Tables\Columns\TextColumn::make('sort_order')
                    ->label(__('messages.admin.partner.order'))
                    ->sortable(),
                
                Tables\Columns\TextColumn::make('created_at')
                    ->label(__('messages.admin.partner.created'))
                    ->dateTime()
                    ->sortable()
                    ->toggleable(isToggledHiddenByDefault: true),
            ])
            ->filters([
                Tables\Filters\TernaryFilter::make('is_active')
                    ->label(__('messages.admin.partner.active'))
                    ->placeholder('All partners')
                    ->trueLabel(__('messages.admin.partner.active_only'))
                    ->falseLabel(__('messages.admin.partner.inactive_only')),
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
            ->defaultSort('sort_order', 'asc');
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
            'index' => Pages\ListPartners::route('/'),
            'create' => Pages\CreatePartner::route('/create'),
            'edit' => Pages\EditPartner::route('/{record}/edit'),
        ];
    }
}
