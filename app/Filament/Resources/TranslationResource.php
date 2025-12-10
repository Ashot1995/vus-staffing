<?php

namespace App\Filament\Resources;

use App\Filament\Resources\TranslationResource\Pages;
use App\Models\Translation;
use Filament\Forms;
use Filament\Forms\Form;
use Filament\Resources\Resource;
use Filament\Tables;
use Filament\Tables\Table;

class TranslationResource extends Resource
{
    protected static ?string $model = Translation::class;

    protected static ?string $navigationIcon = 'heroicon-o-document-text';

    protected static ?string $navigationLabel = 'Translations (Advanced)';

    protected static ?int $navigationSort = 5;
    
    protected static bool $shouldRegisterNavigation = false; // Hide from navigation, use ManageTranslations page instead

    public static function form(Form $form): Form
    {
        return $form
            ->schema([
                Forms\Components\TextInput::make('key')
                    ->label('Translation Key')
                    ->required()
                    ->maxLength(255)
                    ->helperText('e.g., nav.home, home.hero.title')
                    ->disabled(fn ($record) => $record !== null),
                Forms\Components\Select::make('locale')
                    ->label('Language')
                    ->options([
                        'en' => 'English',
                        'sv' => 'Swedish',
                    ])
                    ->required()
                    ->disabled(fn ($record) => $record !== null),
                Forms\Components\Select::make('group')
                    ->label('Group')
                    ->options([
                        'messages' => 'Messages',
                    ])
                    ->default('messages')
                    ->required()
                    ->disabled(fn ($record) => $record !== null),
                Forms\Components\Textarea::make('value')
                    ->label('Translation Value')
                    ->required()
                    ->rows(4)
                    ->columnSpanFull(),
            ]);
    }

    public static function table(Table $table): Table
    {
        return $table
            ->columns([
                Tables\Columns\TextColumn::make('key')
                    ->label('Key')
                    ->searchable()
                    ->sortable(),
                Tables\Columns\TextColumn::make('locale')
                    ->label('Language')
                    ->badge()
                    ->color(fn (string $state): string => match ($state) {
                        'en' => 'success',
                        'sv' => 'info',
                        default => 'gray',
                    })
                    ->sortable(),
                Tables\Columns\TextColumn::make('group')
                    ->label('Group')
                    ->badge()
                    ->sortable(),
                Tables\Columns\TextColumn::make('value')
                    ->label('Translation')
                    ->limit(50)
                    ->searchable()
                    ->wrap(),
                Tables\Columns\TextColumn::make('updated_at')
                    ->label('Updated')
                    ->dateTime()
                    ->sortable()
                    ->toggleable(isToggledHiddenByDefault: true),
            ])
            ->filters([
                Tables\Filters\SelectFilter::make('locale')
                    ->label('Language')
                    ->options([
                        'en' => 'English',
                        'sv' => 'Swedish',
                    ]),
                Tables\Filters\SelectFilter::make('group')
                    ->label('Group')
                    ->options([
                        'messages' => 'Messages',
                    ]),
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
            ->defaultSort('key');
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
            'index' => Pages\ListTranslations::route('/'),
            'create' => Pages\CreateTranslation::route('/create'),
            'edit' => Pages\EditTranslation::route('/{record}/edit'),
        ];
    }
}
