<?php

namespace App\Filament\Resources\Payments\PaymentGatewayResource\Pages;

use App\Filament\Resources\Payments\PaymentGatewayResource;
use Filament\Actions;
use Filament\Resources\Pages\EditRecord;

class EditPaymentGateway extends EditRecord
{
    protected static string $resource = PaymentGatewayResource::class;

    protected function getHeaderActions(): array
    {
        return [
            Actions\DeleteAction::make(),
        ];
    }
}
