<?php

namespace App\Filament\Resources;

use App\Filament\Resources\UserResource\Pages;
use App\Models\User;
use Filament\Forms;
use Filament\Forms\Form;
use Filament\Resources\Resource;
use Filament\Tables;
use Filament\Tables\Table;

class UserResource extends Resource
{
    protected static ?string $model = User::class;

    protected static ?string $navigationIcon = 'heroicon-o-users';

    protected static ?string $navigationLabel = 'Users';

    protected static ?int $navigationSort = 1;

    public static function form(Form $form): Form
    {
        return $form
            ->schema([
                Forms\Components\Tabs::make('User Information')
                    ->tabs([
                        Forms\Components\Tabs\Tab::make('Basic Information')
                            ->schema([
                                Forms\Components\TextInput::make('name')
                                    ->required()
                                    ->maxLength(255),
                                Forms\Components\TextInput::make('email')
                                    ->email()
                                    ->required()
                                    ->maxLength(255),
                                Forms\Components\TextInput::make('password')
                                    ->password()
                                    ->dehydrated(fn ($state) => filled($state))
                                    ->maxLength(255)
                                    ->helperText('Leave empty to keep current password (when editing)')
                                    ->required(fn ($livewire) => $livewire instanceof \App\Filament\Resources\UserResource\Pages\CreateUser),
                                Forms\Components\Toggle::make('is_admin')
                                    ->label('Admin User')
                                    ->helperText('Admin users can access the admin panel'),
                            ])
                            ->columns(2),

                        Forms\Components\Tabs\Tab::make('CV / Resume')
                            ->schema([
                                Forms\Components\Section::make('User CV')
                                    ->description('View or update the user\'s CV')
                                    ->schema([
                                        Forms\Components\ViewField::make('cv_path')
                                            ->label('Current CV')
                                            ->view('filament.components.cv-download')
                                            ->visible(fn ($record) => $record && $record->cv_path),
                                        Forms\Components\FileUpload::make('cv_path')
                                            ->label('Upload/Update CV')
                                            ->directory('cvs')
                                            ->acceptedFileTypes(['application/pdf', 'application/msword', 'application/vnd.openxmlformats-officedocument.wordprocessingml.document'])
                                            ->maxSize(5120)
                                            ->downloadable()
                                            ->deletable(),
                                    ]),
                            ]),

                        Forms\Components\Tabs\Tab::make('Additional Information')
                            ->schema([
                                Forms\Components\DateTimePicker::make('email_verified_at')
                                    ->label('Email Verified At'),
                                Forms\Components\DateTimePicker::make('gdpr_consent_at')
                                    ->label('GDPR Consent At'),
                                Forms\Components\DateTimePicker::make('gdpr_reminder_sent_at')
                                    ->label('GDPR Reminder Sent At'),
                                Forms\Components\Toggle::make('newsletter_subscribed')
                                    ->label('Newsletter Subscribed'),
                            ])
                            ->columns(2),
                    ])
                    ->columnSpanFull(),
            ]);
    }

    public static function table(Table $table): Table
    {
        return $table
            ->columns([
                Tables\Columns\TextColumn::make('name')
                    ->searchable(),
                Tables\Columns\TextColumn::make('email')
                    ->searchable(),
                Tables\Columns\TextColumn::make('email_verified_at')
                    ->dateTime()
                    ->sortable(),
                Tables\Columns\TextColumn::make('gdpr_consent_at')
                    ->dateTime()
                    ->sortable(),
                Tables\Columns\TextColumn::make('gdpr_reminder_sent_at')
                    ->dateTime()
                    ->sortable(),
                Tables\Columns\IconColumn::make('newsletter_subscribed')
                    ->boolean(),
                Tables\Columns\TextColumn::make('created_at')
                    ->dateTime()
                    ->sortable()
                    ->toggleable(isToggledHiddenByDefault: true),
                Tables\Columns\TextColumn::make('updated_at')
                    ->dateTime()
                    ->sortable()
                    ->toggleable(isToggledHiddenByDefault: true),
                Tables\Columns\IconColumn::make('is_admin')
                    ->label('Admin')
                    ->boolean(),
                Tables\Columns\TextColumn::make('applications_count')
                    ->label('Applications')
                    ->counts('applications')
                    ->sortable(),
            ])
            ->filters([
                //
            ])
            ->actions([
                Tables\Actions\ViewAction::make(),
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
            'index' => Pages\ListUsers::route('/'),
            'create' => Pages\CreateUser::route('/create'),
            'view' => Pages\ViewUser::route('/{record}'),
            'edit' => Pages\EditUser::route('/{record}/edit'),
        ];
    }
}
