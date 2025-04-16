<?php

namespace App\Filament\Resources\Features\FeatureResource\Pages;

use App\Filament\Resources\Features\FeatureResource;
use Filament\Actions;
use Filament\Forms\Components\Textarea;
use Filament\Forms\Components\TextInput;
use Filament\Notifications\Notification;
use Filament\Resources\Pages\ListRecords;
use Illuminate\Support\Arr;
use Illuminate\Support\Facades\Artisan;

class ListFeatures extends ListRecords
{
    protected static string $resource = FeatureResource::class;

    protected function getHeaderActions(): array
    {
        return [
            Actions\Action::make('New Feature')
                ->modalWidth('md')
                ->form([
                    TextInput::make('name')
                        ->hint('@eg AddTeamMembers')
                        ->unique('features', 'feature_class')
                        ->live()
                        ->required(),
                    TextInput::make('--title')
                        ->label("Title"),
                    TextInput::make('--feature')  
                        ->label("Feature")
                        ->unique('features', 'shortcode')
                        ->hint('@eg add_team_members'),
                    Textarea::make('--description')
                        ->label("Description")
                ])
                ->action(function(array $data) {
                    Artisan::call("make:feature", $data);
                    Notification::make()
                        ->body('Feature Created Successfully')
                        ->success()->send();
                }),
        ];
    }
}
