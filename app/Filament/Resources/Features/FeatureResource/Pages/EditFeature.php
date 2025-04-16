<?php

namespace App\Filament\Resources\Features\FeatureResource\Pages;

use App\Filament\Resources\Features\FeatureResource;
use Filament\Actions;
use Filament\Resources\Pages\EditRecord;

class EditFeature extends EditRecord
{
    protected static string $resource = FeatureResource::class;

    protected function getHeaderActions(): array
    {
        return [
            // Actions\DeleteAction::make(),
        ];
    }
}
