<?php

namespace App\Filament\Resources;

use App\Filament\Resources\ApplicationResource\Pages;
use App\Models\Application;
use Filament\Forms;
use Filament\Forms\Form;
use Filament\Resources\Resource;
use Filament\Tables;
use Filament\Tables\Table;

class ApplicationResource extends Resource
{
    protected static ?string $model = Application::class;

    protected static ?string $navigationIcon = 'heroicon-o-document-text';

    protected static ?string $navigationLabel = 'Applications';

    protected static ?int $navigationSort = 2;

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
                                    ->label(__('messages.admin.application.job_position')),
                                Forms\Components\Select::make('user_id')
                                    ->relationship('user', 'name')
                                    ->required()
                                    ->searchable()
                                    ->preload()
                                    ->label(__('messages.admin.application.applicant')),
                                Forms\Components\Toggle::make('is_spontaneous')
                                    ->label(__('messages.admin.application.spontaneous')),
                                Forms\Components\Select::make('status')
                                    ->options([
                                        'pending' => __('messages.admin.status.pending'),
                                        'reviewed' => __('messages.admin.status.reviewed'),
                                        'shortlisted' => __('messages.admin.status.shortlisted'),
                                        'rejected' => __('messages.admin.status.rejected'),
                                        'accepted' => __('messages.admin.status.accepted'),
                                    ])
                                    ->default('pending')
                                    ->required(),
                            ])
                            ->columns(2),

                        Forms\Components\Tabs\Tab::make('Personal Information')
                            ->schema([
                                Forms\Components\TextInput::make('first_name')
                                    ->label(__('messages.admin.application.first_name'))
                                    ->required()
                                    ->maxLength(255),
                                
                                Forms\Components\TextInput::make('surname')
                                    ->label(__('messages.admin.application.surname'))
                                    ->required()
                                    ->maxLength(255),
                                
                                Forms\Components\DatePicker::make('date_of_birth')
                                    ->label(__('messages.admin.application.date_of_birth'))
                                    ->displayFormat('Y-m-d')
                                    ->required(),
                                
                                Forms\Components\TextInput::make('email')
                                    ->label(__('messages.admin.application.email'))
                                    ->email()
                                    ->required()
                                    ->maxLength(255),
                                
                                Forms\Components\TextInput::make('phone')
                                    ->label(__('messages.admin.application.phone'))
                                    ->tel()
                                    ->required()
                                    ->maxLength(255),
                                
                                Forms\Components\Textarea::make('address')
                                    ->label(__('messages.admin.application.address'))
                                    ->required()
                                    ->maxLength(500)
                                    ->rows(2),
                            ])
                            ->columns(2),

                        Forms\Components\Tabs\Tab::make('CV & Documents')
                            ->schema([
                                Forms\Components\Section::make('CV Document')
                                    ->description('View or download the applicant\'s CV')
                                    ->schema([
                                        Forms\Components\ViewField::make('cv_path')
                                            ->label(__('messages.admin.application.current_cv'))
                                            ->view('filament.components.cv-download')
                                            ->visible(fn ($record) => $record && $record->cv_path),
                                        Forms\Components\FileUpload::make('cv_path')
                                            ->label(__('messages.admin.application.upload_cv'))
                                            ->directory('cvs')
                                            ->disk('public')
                                            ->acceptedFileTypes(['application/pdf', 'application/msword', 'application/vnd.openxmlformats-officedocument.wordprocessingml.document'])
                                            ->maxSize(3072)
                                            ->downloadable()
                                            ->deletable(),
                                    ]),
                                Forms\Components\Section::make('Personal Image')
                                    ->description('Applicant\'s personal photo')
                                    ->schema([
                                        Forms\Components\ViewField::make('personal_image_path')
                                            ->label(__('messages.admin.application.current_image'))
                                            ->view('filament.components.image-preview')
                                            ->visible(fn ($record) => $record && $record->personal_image_path),
                                        Forms\Components\FileUpload::make('personal_image_path')
                                            ->label(__('messages.admin.application.upload_image'))
                                            ->directory('personal-images')
                                            ->disk('public')
                                            ->image()
                                            ->acceptedFileTypes(['image/*'])
                                            ->maxSize(2048)
                                            ->imageEditor()
                                            ->downloadable()
                                            ->deletable(),
                                    ]),
                            ]),

                        Forms\Components\Tabs\Tab::make('Additional Information')
                            ->schema([
                                Forms\Components\Section::make('Driving License Privileges')
                                    ->schema([
                                        Forms\Components\Toggle::make('driving_license_b')
                                            ->label(__('messages.admin.application.driving_license_b'))
                                            ->default(false),
                                        
                                        Forms\Components\Toggle::make('driving_license_own_car')
                                            ->label(__('messages.admin.application.own_car'))
                                            ->default(false),
                                    ])
                                    ->columns(2),
                                
                                Forms\Components\Section::make('Availability & Other')
                                    ->schema([
                                        Forms\Components\Select::make('start_date_option')
                                            ->label(__('messages.admin.application.availability'))
                                            ->options([
                                                'immediately' => 'Immediately',
                                                'one_month' => 'Within a month',
                                                'two_three_months' => 'Within two to three months',
                                            ])
                                            ->required()
                                            ->live()
                                            ->afterStateUpdated(function ($state, callable $set) {
                                                $calculatedDate = match ($state) {
                                                    'immediately' => now()->toDateString(),
                                                    'one_month' => now()->addMonth()->toDateString(),
                                                    'two_three_months' => now()->addMonths(2)->addDays(15)->toDateString(),
                                                    default => null,
                                                };
                                                $set('start_date', $calculatedDate);
                                            }),
                                        
                                        Forms\Components\DatePicker::make('start_date')
                                            ->label(__('messages.admin.application.start_date'))
                                            ->displayFormat('Y-m-d')
                                            ->disabled()
                                            ->dehydrated(),
                                        
                                        Forms\Components\Textarea::make('other')
                                            ->label(__('messages.admin.application.other'))
                                            ->rows(3)
                                            ->maxLength(1000)
                                            ->columnSpanFull(),
                                    ])
                                    ->columns(2),
                                
                                Forms\Components\Section::make('Consent')
                                    ->schema([
                                        Forms\Components\Select::make('consent_type')
                                            ->label(__('messages.admin.application.consent_type'))
                                            ->options([
                                                'full' => 'Full Consent - Can be used for matching with other jobs',
                                                'limited' => 'Limited Consent - Only for this application',
                                            ])
                                            ->required(),
                                    ]),
                            ]),

                        Forms\Components\Tabs\Tab::make('Timeline')
                            ->schema([
                                Forms\Components\DateTimePicker::make('created_at')
                                    ->label(__('messages.admin.application.applied_date'))
                                    ->disabled()
                                    ->format('Y-m-d H:i'),
                                Forms\Components\DateTimePicker::make('updated_at')
                                    ->label(__('messages.admin.application.last_updated'))
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
                    ->label('Job')
                    ->sortable()
                    ->searchable(),
                Tables\Columns\IconColumn::make('is_spontaneous')
                    ->label(__('messages.admin.application.type'))
                    ->boolean()
                    ->trueIcon('heroicon-o-sparkles')
                    ->falseIcon('heroicon-o-briefcase')
                    ->trueColor('info')
                    ->falseColor('gray'),
                Tables\Columns\TextColumn::make('first_name')
                    ->label(__('messages.admin.application.first_name'))
                    ->searchable()
                    ->sortable(),
                Tables\Columns\TextColumn::make('surname')
                    ->label(__('messages.admin.application.surname'))
                    ->searchable()
                    ->sortable(),
                Tables\Columns\TextColumn::make('email')
                    ->label(__('messages.admin.application.email'))
                    ->searchable()
                    ->sortable(),
                Tables\Columns\TextColumn::make('phone')
                    ->label(__('messages.admin.application.phone'))
                    ->searchable(),
                Tables\Columns\TextColumn::make('date_of_birth')
                    ->label(__('messages.admin.application.date_of_birth'))
                    ->date()
                    ->sortable(),
                Tables\Columns\TextColumn::make('address')
                    ->label(__('messages.admin.application.address'))
                    ->searchable()
                    ->limit(30)
                    ->tooltip(fn ($record) => $record->address),
                Tables\Columns\IconColumn::make('driving_license_b')
                    ->label(__('messages.admin.application.b_license'))
                    ->boolean(),
                Tables\Columns\IconColumn::make('driving_license_own_car')
                    ->label(__('messages.admin.application.own_car'))
                    ->boolean(),
                Tables\Columns\TextColumn::make('status')
                    ->badge()
                    ->colors([
                        'secondary' => 'pending',
                        'info' => 'reviewed',
                        'success' => 'shortlisted',
                        'danger' => 'rejected',
                        'primary' => 'accepted',
                    ])
                    ->sortable(),
                Tables\Columns\TextColumn::make('start_date_option')
                    ->label(__('messages.admin.application.availability'))
                    ->formatStateUsing(fn ($state) => match ($state) {
                        'immediately' => 'Immediately',
                        'one_month' => 'Within a month',
                        'two_three_months' => 'Within 2-3 months',
                        default => '-',
                    })
                    ->badge()
                    ->sortable(),
                Tables\Columns\TextColumn::make('start_date')
                    ->label(__('messages.admin.application.expected_start'))
                    ->date()
                    ->sortable(),
                Tables\Columns\TextColumn::make('consent_type')
                    ->label(__('messages.admin.application.consent'))
                    ->formatStateUsing(fn ($state) => match ($state) {
                        'full' => 'Full',
                        'limited' => 'Limited',
                        default => '-',
                    })
                    ->badge()
                    ->colors([
                        'success' => 'full',
                        'warning' => 'limited',
                    ]),
                Tables\Columns\TextColumn::make('created_at')
                    ->label(__('messages.admin.application.applied_date'))
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
