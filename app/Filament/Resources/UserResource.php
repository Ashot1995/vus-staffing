<?php

namespace App\Filament\Resources;

use App\Filament\Resources\UserResource\Pages;
use App\Filament\Resources\UserResource\RelationManagers;
use App\Models\User;
use Filament\Forms;
use Filament\Forms\Form;
use Filament\Resources\Resource;
use Filament\Tables;
use Filament\Tables\Table;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\SoftDeletingScope;

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
                Forms\Components\Section::make('User Information')
                    ->schema([
                        Forms\Components\TextInput::make('name')
                            ->required()
                            ->maxLength(255),
                        Forms\Components\TextInput::make('email')
                            ->email()
                            ->required()
                            ->maxLength(255)
                            ->unique(ignoreRecord: true),
                        Forms\Components\TextInput::make('password')
                            ->password()
                            ->maxLength(255)
                            ->dehydrated(fn ($state) => filled($state))
                            ->dehydrateStateUsing(fn ($state) => bcrypt($state))
                            ->required(fn (string $context): bool => $context === 'create'),
                        Forms\Components\Toggle::make('is_admin')
                            ->label('Admin User')
                            ->default(false),
                    ])
                    ->columns(2),
                
                Forms\Components\Section::make('CV / Resume')
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
                
                Forms\Components\Section::make('Additional Information')
                    ->schema([
                        Forms\Components\DateTimePicker::make('email_verified_at')
                            ->label('Email Verified At'),
                        Forms\Components\DateTimePicker::make('gdpr_consent_at')
                            ->label('GDPR Consent At'),
                        Forms\Components\DateTimePicker::make('gdpr_reminder_sent_at')
                            ->label('GDPR Reminder Sent At'),
                        Forms\Components\Toggle::make('newsletter_subscribed')
                            ->label('Newsletter Subscribed')
                            ->default(false),
                    ])
                    ->columns(2)
                    ->collapsible(),
            ]);
    }

    public static function table(Table $table): Table
    {
        return $table
            ->columns([
                Tables\Columns\TextColumn::make('name')
                    ->searchable()
                    ->sortable(),
                Tables\Columns\TextColumn::make('email')
                    ->searchable()
                    ->sortable()
                    ->copyable(),
                Tables\Columns\IconColumn::make('is_admin')
                    ->label('Admin')
                    ->boolean()
                    ->sortable(),
                Tables\Columns\IconColumn::make('cv_path')
                    ->label('Has CV')
                    ->boolean()
                    ->getStateUsing(fn ($record) => !empty($record->cv_path)),
                Tables\Columns\TextColumn::make('applications_count')
                    ->label('Applications')
                    ->counts('applications')
                    ->sortable(),
                Tables\Columns\TextColumn::make('email_verified_at')
                    ->label('Verified')
                    ->dateTime()
                    ->sortable()
                    ->toggleable(isToggledHiddenByDefault: true),
                Tables\Columns\TextColumn::make('created_at')
                    ->label('Registered')
                    ->dateTime()
                    ->sortable(),
            ])
            ->filters([
                Tables\Filters\TernaryFilter::make('is_admin')
                    ->label('Admin Users'),
                Tables\Filters\TernaryFilter::make('cv_path')
                    ->label('Has CV')
                    ->placeholder('All users')
                    ->trueLabel('With CV')
                    ->falseLabel('Without CV')
                    ->queries(
                        true: fn (Builder $query) => $query->whereNotNull('cv_path'),
                        false: fn (Builder $query) => $query->whereNull('cv_path'),
                    ),
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
