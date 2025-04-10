<?php

namespace App\Filament\Resources\Payments\PaymentGatewayResource\Pages;

use App\Filament\Resources\Payments\PaymentGatewayResource;
use Filament\Actions;
use Filament\Resources\Pages\CreateRecord;

class CreatePaymentGateway extends CreateRecord
{
    protected static string $resource = PaymentGatewayResource::class;
}
