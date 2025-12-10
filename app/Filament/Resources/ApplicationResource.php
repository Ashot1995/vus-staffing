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
                                Forms\Components\Section::make('CV Document')
                                    ->description('View or download the applicant\'s CV')
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
                                Forms\Components\Section::make('Cover Letter')
                                    ->description('Read the applicant\'s cover letter')
                                    ->schema([
                                        Forms\Components\Textarea::make('cover_letter')
                                            ->label('Cover Letter Content')
                                            ->rows(15)
                                            ->columnSpanFull()
                                            ->extraAttributes(['class' => 'font-mono text-sm']),
                                    ]),
                            ]),

                        Forms\Components\Tabs\Tab::make('Dates & Timeline')
                            ->schema([
                                Forms\Components\Select::make('start_date_option')
                                    ->label('Start Date Option')
                                    ->options([
                                        'immediately' => 'Immediately',
                                        'one_week' => 'After one week',
                                        'one_month' => 'After one month',
                                        'custom' => 'Custom date',
                                    ])
                                    ->live()
                                    ->afterStateUpdated(function ($state, callable $set, $get) {
                                        if ($state !== 'custom') {
                                            // Calculate date based on option
                                            $calculatedDate = match ($state) {
                                                'immediately' => now()->toDateString(),
                                                'one_week' => now()->addWeek()->toDateString(),
                                                'one_month' => now()->addMonth()->toDateString(),
                                                default => null,
                                            };
                                            $set('start_date', $calculatedDate);
                                        }
                                    }),
                                Forms\Components\DatePicker::make('start_date')
                                    ->label('Expected Start Date')
                                    ->displayFormat('Y-m-d')
                                    ->visible(fn ($get) => $get('start_date_option') === 'custom')
                                    ->required(fn ($get) => $get('start_date_option') === 'custom')
                                    ->helperText('Only required when "Custom date" is selected'),
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
                    ->formatStateUsing(fn ($state) => match ($state) {
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
