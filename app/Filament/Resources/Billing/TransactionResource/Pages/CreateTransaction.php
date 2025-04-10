<?php

namespace App\Filament\Resources\Billing\TransactionResource\Pages;

use App\Filament\Resources\Billing\TransactionResource;
use Filament\Actions;
use Filament\Resources\Pages\CreateRecord;

class CreateTransaction extends CreateRecord
{
    protected static string $resource = TransactionResource::class;
}
