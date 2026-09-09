<?php

namespace App\Filament\Resources\LessonResource\RelationManagers;

use App\Enums\QuestionDifficultyEnum;
use App\Enums\QuestionTypeEnum;
use Filament\Forms;
use Filament\Forms\Form;
use Filament\Resources\RelationManagers\RelationManager;
use Filament\Tables;
use Filament\Tables\Table;

class QuestionsRelationManager extends RelationManager
{
    protected static string $relationship = 'questions';

    public function form(Form $form): Form
    {
        return $form
            ->schema([
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
                Forms\Components\FileUpload::make('audio_url')
                    ->label('Audio')
                    ->directory('questions/audio')
                    ->acceptedFileTypes(['audio/mpeg', 'audio/mp3', 'audio/wav', 'audio/ogg'])
                    ->maxSize(10240)
                    ->preserveFilenames(false)
                    ->visibility('public')
                    ->storeFileNamesIn('audio_url'),
                Forms\Components\TextInput::make('order')
                    ->required()
                    ->numeric(),
            ]);
    }

    public function table(Table $table): Table
    {
        return $table
            ->recordTitleAttribute('question_text')
            ->columns([
                Tables\Columns\TextColumn::make('question_text')->limit(40),
                Tables\Columns\TextColumn::make('type'),
                Tables\Columns\TextColumn::make('difficulty_level'),
                Tables\Columns\TextColumn::make('order')->sortable(),
            ])
            ->filters([
                //
            ])
            ->headerActions([
                Tables\Actions\CreateAction::make(),
            ])
            ->actions([
                Tables\Actions\EditAction::make(),
                Tables\Actions\DeleteAction::make(),
            ])
            ->bulkActions([
                Tables\Actions\BulkActionGroup::make([
                    Tables\Actions\DeleteBulkAction::make(),
                ]),
            ]);
    }
}
