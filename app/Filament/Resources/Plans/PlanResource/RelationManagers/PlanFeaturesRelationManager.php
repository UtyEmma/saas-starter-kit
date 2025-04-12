<?php

namespace App\Filament\Resources\Plans\PlanResource\RelationManagers;

use App\Enums\Timelines;
use Filament\Forms;
use Filament\Forms\Form;
use Filament\Resources\RelationManagers\RelationManager;
use Filament\Tables;
use Filament\Tables\Table;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\SoftDeletingScope;

class PlanFeaturesRelationManager extends RelationManager
{
    protected static string $relationship = 'planFeatures';

    public function form(Form $form): Form
    {
        return $form
            ->schema([
                Forms\Components\Select::make('feature_id')
                    ->relationship('feature', 'name')
                    ->required(),
                Forms\Components\TextInput::make('limit')
                    ->numeric(),
                Forms\Components\TextInput::make('reset_period'),
                Forms\Components\Select::make('reset_interval')
                    ->native(false)
                    ->options(Timelines::options()),
            ]);
    }

    public function table(Table $table): Table
    {
        return $table
            ->recordTitleAttribute('feature_id')
            ->columns([
                Tables\Columns\TextColumn::make('feature.name'),
                Tables\Columns\TextColumn::make('limit'),
                Tables\Columns\TextColumn::make('reset_interval'),
                Tables\Columns\TextColumn::make('reset_period'),
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
