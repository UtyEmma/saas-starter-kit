<?php

namespace App\Filament\Resources\Locale\CountryResource\Pages;

use App\Filament\Resources\Locale\CountryResource;
use Filament\Actions;
use Filament\Resources\Pages\ListRecords;

class ListCountries extends ListRecords
{
    protected static string $resource = CountryResource::class;

    protected function getHeaderActions(): array
    {
        return [
            Actions\CreateAction::make(),
        ];
    }
}
