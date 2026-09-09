<?php

namespace App\Filament\Widgets;

use App\Models\User;
use Filament\Tables;
use Filament\Tables\Table;
use Filament\Widgets\TableWidget;

class RecentUsersTableWidget extends TableWidget
{
    protected int|string|array $columnSpan = 'full';

    protected static ?string $heading = 'Recent Users';

    public function table(Table $table): Table
    {
        return $table
            ->query(User::query()->whereDoesntHave('roles', fn ($q) => $q->where('name', 'admin'))->latest()->limit(10))
            ->columns([
                Tables\Columns\TextColumn::make('name')
                    ->searchable(),
                Tables\Columns\TextColumn::make('email')
                    ->searchable(),
                Tables\Columns\TextColumn::make('xp_total')
                    ->sortable()
                    ->label('XP Total'),
                Tables\Columns\TextColumn::make('current_streak')
                    ->sortable()
                    ->label('Streak')
                    ->toggleable(isToggledHiddenByDefault: true),
                Tables\Columns\TextColumn::make('created_at')
                    ->dateTime('d M Y')
                    ->sortable()
                    ->label('Joined')
                    ->toggleable(isToggledHiddenByDefault: true),
            ]);
    }
}
