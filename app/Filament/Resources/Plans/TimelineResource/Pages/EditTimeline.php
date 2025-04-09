<?php

namespace App\Filament\Resources\Plans\TimelineResource\Pages;

use App\Filament\Resources\Plans\TimelineResource;
use Filament\Actions;
use Filament\Resources\Pages\EditRecord;

class EditTimeline extends EditRecord
{
    protected static string $resource = TimelineResource::class;

    protected function getHeaderActions(): array
    {
        return [
            Actions\DeleteAction::make(),
        ];
    }
}
