<?php

namespace App\Filament\Resources;

use App\Filament\Resources\ApplicationResource\Pages;
use App\Filament\Resources\ApplicationResource\RelationManagers;
use App\Models\Application;
use Filament\Forms;
use Filament\Forms\Form;
use Filament\Resources\Resource;
use Filament\Tables;
use Filament\Tables\Table;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\SoftDeletingScope;

class ApplicationResource extends Resource
{
    protected static ?string $model = Application::class;

    protected static ?string $navigationIcon = 'heroicon-o-document-text';
    
    protected static ?string $navigationLabel = 'Applications';
    
    protected static ?int $navigationSort = 3;

    public static function form(Form $form): Form
    {
        return $form
            ->schema([
                Forms\Components\Tabs::make('Application Details')
                    ->tabs([
                        Forms\Components\Tabs\Tab::make('Basic Information')
                            ->schema([
                                Forms\Components\Select::make('job_id')
                                    ->relationship('job', 'title')
                                    ->searchable()
                                    ->preload()
                                    ->label('Job Position'),
                                Forms\Components\Select::make('user_id')
                                    ->relationship('user', 'name')
                                    ->required()
                                    ->searchable()
                                    ->preload()
                                    ->label('Applicant'),
                                Forms\Components\Toggle::make('is_spontaneous')
                                    ->label('Spontaneous Application'),
                                Forms\Components\Select::make('status')
                                    ->options([
                                        'pending' => 'Pending',
                                        'reviewed' => 'Reviewed',
                                        'shortlisted' => 'Shortlisted',
                                        'rejected' => 'Rejected',
                                        'accepted' => 'Accepted',
                                    ])
                                    ->default('pending')
                                    ->required(),
                            ])
                            ->columns(2),
                        
                        Forms\Components\Tabs\Tab::make('CV & Cover Letter')
                            ->schema([
                                Forms\Components\Section::make('Application CV')
                                    ->description('CV uploaded with this specific application')
                                    ->schema([
                                        Forms\Components\ViewField::make('cv_path')
                                            ->label('Application CV')
                                            ->view('filament.components.cv-download')
                                            ->visible(fn ($record) => $record && $record->cv_path),
                                        Forms\Components\FileUpload::make('cv_path')
                                            ->label('Upload/Update Application CV')
                                            ->directory('cvs')
                                            ->acceptedFileTypes(['application/pdf', 'application/msword', 'application/vnd.openxmlformats-officedocument.wordprocessingml.document'])
                                            ->maxSize(5120)
                                            ->downloadable()
                                            ->deletable(),
                                    ]),
                                Forms\Components\Section::make('User Profile CV')
                                    ->description('CV from user\'s profile (if available)')
                                    ->schema([
                                        Forms\Components\ViewField::make('user.cv_path')
                                            ->label('Profile CV')
                                            ->view('filament.components.cv-download')
                                            ->visible(fn ($record) => $record && $record->user && $record->user->cv_path),
                                        Forms\Components\Placeholder::make('no_profile_cv')
                                            ->label('')
                                            ->content('User has not uploaded a profile CV.')
                                            ->visible(fn ($record) => !$record || !$record->user || !$record->user->cv_path),
                                    ]),
                                Forms\Components\Section::make('Cover Letter')
                                    ->description('Read the applicant\'s cover letter')
                                    ->schema([
                                        Forms\Components\Textarea::make('cover_letter')
                                            ->label('Cover Letter Content')
                                            ->rows(15)
                                            ->columnSpanFull()
                                            ->disabled()
                                            ->extraAttributes(['class' => 'font-mono text-sm']),
                                    ]),
                            ]),
                        
                        Forms\Components\Tabs\Tab::make('Dates & Timeline')
                            ->schema([
                                Forms\Components\TextInput::make('start_date_option')
                                    ->label('Start Date Option')
                                    ->disabled()
                                    ->formatStateUsing(fn ($state) => match($state) {
                                        'immediately' => 'Immediately',
                                        'one_week' => 'After one week',
                                        'one_month' => 'After one month',
                                        'custom' => 'Custom date',
                                        default => $state,
                                    }),
                                Forms\Components\DatePicker::make('start_date')
                                    ->label('Expected Start Date')
                                    ->disabled()
                                    ->displayFormat('Y-m-d'),
                                Forms\Components\DateTimePicker::make('created_at')
                                    ->label('Application Date')
                                    ->disabled()
                                    ->format('Y-m-d H:i'),
                                Forms\Components\DateTimePicker::make('updated_at')
                                    ->label('Last Updated')
                                    ->disabled()
                                    ->format('Y-m-d H:i'),
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
                Tables\Columns\TextColumn::make('job.title')
                    ->sortable()
                    ->searchable(),
                Tables\Columns\TextColumn::make('user.name')
                    ->sortable()
                    ->searchable(),
                Tables\Columns\TextColumn::make('user.email')
                    ->sortable()
                    ->searchable(),
                Tables\Columns\IconColumn::make('is_spontaneous')
                    ->boolean(),
                Tables\Columns\TextColumn::make('status')
                    ->badge()
                    ->colors([
                        'secondary' => 'pending',
                        'info' => 'reviewed',
                        'success' => 'shortlisted',
                        'danger' => 'rejected',
                        'primary' => 'accepted',
                    ]),
                Tables\Columns\TextColumn::make('start_date_option')
                    ->label('Start Date')
                    ->formatStateUsing(fn ($state) => match($state) {
                        'immediately' => 'Immediately',
                        'one_week' => 'After 1 week',
                        'one_month' => 'After 1 month',
                        'custom' => 'Custom',
                        default => '-',
                    })
                    ->badge()
                    ->sortable(),
                Tables\Columns\TextColumn::make('start_date')
                    ->label('Expected Start')
                    ->date()
                    ->sortable(),
                Tables\Columns\TextColumn::make('created_at')
                    ->label('Applied Date')
                    ->dateTime()
                    ->sortable(),
            ])
            ->filters([
                Tables\Filters\SelectFilter::make('status')
                    ->options([
                        'pending' => 'Pending',
                        'reviewed' => 'Reviewed',
                        'shortlisted' => 'Shortlisted',
                        'rejected' => 'Rejected',
                        'accepted' => 'Accepted',
                    ]),
                Tables\Filters\TernaryFilter::make('is_spontaneous'),
            ])
            ->actions([
                Tables\Actions\ViewAction::make(),
                Tables\Actions\EditAction::make(),
                Tables\Actions\Action::make('view_user')
                    ->label('View User')
                    ->icon('heroicon-o-user')
                    ->url(fn ($record) => \App\Filament\Resources\UserResource::getUrl('view', ['record' => $record->user_id]))
                    ->visible(fn ($record) => $record->user_id),
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
            'index' => Pages\ListApplications::route('/'),
            'create' => Pages\CreateApplication::route('/create'),
            'view' => Pages\ViewApplication::route('/{record}'),
            'edit' => Pages\EditApplication::route('/{record}/edit'),
        ];
    }
}
