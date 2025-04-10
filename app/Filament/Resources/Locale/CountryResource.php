<?php

namespace App\Filament\Resources\Locale;

use App\Filament\Resources\Locale\CountryResource\Pages;
use App\Filament\Resources\CountryResource\RelationManagers;
use App\Forms\Components\SelectCurrency;
use App\Forms\Components\SelectStatus;
use App\Models\Country;
use App\Tables\Columns\StatusColumn;
use Filament\Forms;
use Filament\Forms\Components\Toggle;
use Filament\Forms\Form;
use Filament\Resources\Resource;
use Filament\Tables;
use Filament\Tables\Table;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\SoftDeletingScope;

class CountryResource extends Resource
{
    protected static ?string $model = Country::class;

    protected static ?string $navigationIcon = 'heroicon-o-flag';

    protected static ?string $navigationGroup = 'Locale';

    public static function form(Form $form): Form
    {
        return $form
            ->schema([
                Forms\Components\Toggle::make('is_default')
                    ->columnSpanFull()
                    ->required(),
                Forms\Components\TextInput::make('name')
                    ->required()
                    ->columnSpanFull()
                    ->maxLength(255),
                Forms\Components\TextInput::make('iso_code')
                    ->required()
                    ->maxLength(255),
                Forms\Components\TextInput::make('iso_code_3')
                    ->required()
                    ->maxLength(255),
                Forms\Components\TextInput::make('intl_phone')
                    ->tel()
                    ->required()
                    ->maxLength(255),
                SelectCurrency::make('currency.name')
                    ->relationship(name: 'currency', titleAttribute: 'name')
                    ->label("Currency")
                    ->native(false)
                    ->createOptionForm([
                        Forms\Components\TextInput::make('name')
                            ->required(),
                        Forms\Components\TextInput::make('code')
                            ->required(),
                        Forms\Components\TextInput::make('rate')
                            ->numeric()
                            ->required(),
                        Forms\Components\TextInput::make('symbol')
                            ->required(),
                        Toggle::make('is_default')
                            ->required(),
                    ]),
                SelectStatus::make('status')
                    ->native(false)
                    ->required()

            ]);
    }

    public static function table(Table $table): Table
    {
        return $table
            ->columns([
                Tables\Columns\TextColumn::make('name')
                    ->searchable(),
                Tables\Columns\TextColumn::make('currency.name')
                    ->searchable(),
                // Tables\Columns\TextColumn::make('iso_code')
                //     ->searchable(),
                Tables\Columns\TextColumn::make('iso_code_3')
                    ->searchable(),
                Tables\Columns\TextColumn::make('intl_phone')
                    ->searchable(),
                Tables\Columns\IconColumn::make('is_default')
                    ->boolean(),
                StatusColumn::make('status'),
                Tables\Columns\TextColumn::make('deleted_at')
                    ->dateTime()
                    ->sortable()
                    ->toggleable(isToggledHiddenByDefault: true),
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
            'index' => Pages\ListCountries::route('/'),
            'create' => Pages\CreateCountry::route('/create'),
            'edit' => Pages\EditCountry::route('/{record}/edit'),
        ];
    }
}
