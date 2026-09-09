<?php

namespace App\Filament\Resources;

use App\Enums\QuestionDifficultyEnum;
use App\Enums\QuestionTypeEnum;
use App\Filament\Resources\QuestionResource\Pages;
use App\Filament\Resources\QuestionResource\RelationManagers;
use App\Models\Question;
use Filament\Forms;
use Filament\Forms\Form;
use Filament\Resources\Resource;
use Filament\Tables;
use Filament\Tables\Table;

class QuestionResource extends Resource
{
    protected static ?string $model = Question::class;

    protected static ?string $navigationGroup = 'Content Management';

    protected static ?string $navigationIcon = 'heroicon-o-question-mark-circle';

    public static function form(Form $form): Form
    {
        return $form
            ->schema([
                Forms\Components\Select::make('lesson_id')
                    ->relationship('lesson', 'title')
                    ->required()
                    ->searchable()
                    ->preload(),
                Forms\Components\Select::make('type')
                    ->options(QuestionTypeEnum::class)
                    ->required()
                    ->native(false),
                Forms\Components\Select::make('difficulty_level')
                    ->options(QuestionDifficultyEnum::class)
                    ->required()
                    ->native(false),
                Forms\Components\Textarea::make('question_text')
                    ->required()
                    ->columnSpanFull(),
                Forms\Components\TextInput::make('audio_url')
                    ->url()
                    ->maxLength(2048),
                Forms\Components\TextInput::make('order')
                    ->required()
                    ->numeric(),
            ]);
    }

    public static function table(Table $table): Table
    {
        return $table
            ->columns([
                Tables\Columns\TextColumn::make('lesson.title')
                    ->numeric()
                    ->sortable(),
                Tables\Columns\TextColumn::make('type'),
                Tables\Columns\TextColumn::make('difficulty_level'),
                Tables\Columns\TextColumn::make('audio_url')
                    ->searchable(),
                Tables\Columns\TextColumn::make('order')
                    ->numeric()
                    ->sortable(),
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
                Tables\Actions\EditAction::make()
                    ->modal(),
                Tables\Actions\DeleteAction::make()
                    ->modal(),
            ])
            ->bulkActions([
                Tables\Actions\BulkActionGroup::make([
                    Tables\Actions\DeleteBulkAction::make()
                        ->modal(),
                ]),
            ]);
    }

    public static function getRelations(): array
    {
        return [
            RelationManagers\OptionsRelationManager::class,
            RelationManagers\AnswerRelationManager::class,
        ];
    }

    public static function getPages(): array
    {
        return [
            'index' => Pages\ListQuestions::route('/'),
            // 'create' => Pages\CreateQuestion::route('/create'),
            // 'edit' => Pages\EditQuestion::route('/{record}/edit'),
        ];
    }
}
