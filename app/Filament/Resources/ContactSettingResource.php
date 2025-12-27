<?php

namespace App\Filament\Resources;

use App\Filament\Resources\ContactSettingResource\Pages;
use App\Filament\Resources\ContactSettingResource\RelationManagers;
use App\Models\ContactSetting;
use Filament\Forms;
use Filament\Forms\Form;
use Filament\Resources\Resource;
use Filament\Tables;
use Filament\Tables\Table;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\SoftDeletingScope;

class ContactSettingResource extends Resource
{
    protected static ?string $model = ContactSetting::class;

    protected static ?string $navigationLabel = 'Contact Us';

    protected static ?string $navigationIcon = 'heroicon-o-envelope';

    protected static ?string $navigationGroup = 'Content Management';

    protected static ?int $navigationSort = 7;

    public static function form(Form $form): Form
    {
        return $form
            ->schema([
                Forms\Components\Section::make('Contact Information')
                    ->schema([
                        Forms\Components\TextInput::make('email')
                            ->label(__('messages.admin.contact.email'))
                            ->email()
                            ->required()
                            ->maxLength(255)
                            ->default('abdulrazek.mahmoud@vus-bemanning.se')
                            ->columnSpanFull(),
                        
                        Forms\Components\TextInput::make('phone')
                            ->label(__('messages.admin.contact.phone'))
                            ->tel()
                            ->maxLength(255)
                            ->columnSpanFull(),
                    ])
                    ->columns(2),

                Forms\Components\Section::make('Address')
                    ->schema([
                        Forms\Components\Textarea::make('address_en')
                            ->label(__('messages.admin.contact.address_en'))
                            ->rows(2)
                            ->columnSpan(1),
                        
                        Forms\Components\Textarea::make('address_sv')
                            ->label(__('messages.admin.contact.address_sv'))
                            ->rows(2)
                            ->columnSpan(1),
                    ])
                    ->columns(2),

                Forms\Components\Section::make('Business Hours')
                    ->schema([
                        Forms\Components\TextInput::make('hours_weekdays_en')
                            ->label(__('messages.admin.contact.hours_weekdays_en'))
                            ->placeholder('Monday - Friday: 9:00 AM - 5:00 PM')
                            ->maxLength(255)
                            ->columnSpan(1),
                        
                        Forms\Components\TextInput::make('hours_weekdays_sv')
                            ->label(__('messages.admin.contact.hours_weekdays_sv'))
                            ->placeholder('Måndag - Fredag: 9:00 - 17:00')
                            ->maxLength(255)
                            ->columnSpan(1),
                        
                        Forms\Components\TextInput::make('hours_weekend_en')
                            ->label(__('messages.admin.contact.hours_weekend_en'))
                            ->placeholder('Saturday - Sunday: Closed')
                            ->maxLength(255)
                            ->columnSpan(1),
                        
                        Forms\Components\TextInput::make('hours_weekend_sv')
                            ->label(__('messages.admin.contact.hours_weekend_sv'))
                            ->placeholder('Lördag - Söndag: Stängt')
                            ->maxLength(255)
                            ->columnSpan(1),
                    ])
                    ->columns(2),
            ]);
    }

    public static function table(Table $table): Table
    {
        return $table
            ->columns([
                Tables\Columns\TextColumn::make('email')
                    ->searchable(),
                Tables\Columns\TextColumn::make('phone')
                    ->searchable(),
                Tables\Columns\TextColumn::make('created_at')
                    ->dateTime()
                    ->sortable()
                    ->toggleable(isToggledHiddenByDefault: true),
                Tables\Columns\TextColumn::make('updated_at')
                    ->dateTime()
                    ->sortable()
                    ->toggleable(isToggledHiddenByDefault: true),
            ])
            ->filters([
                //
            ])
            ->actions([
                Tables\Actions\EditAction::make(),
            ])
            ->bulkActions([
                Tables\Actions\BulkActionGroup::make([
                    Tables\Actions\DeleteBulkAction::make(),
                ]),
            ]);
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
            'index' => Pages\ListContactSettings::route('/'),
            'create' => Pages\CreateContactSetting::route('/create'),
            'edit' => Pages\EditContactSetting::route('/{record}/edit'),
        ];
    }
}
