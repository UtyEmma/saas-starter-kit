<?php

namespace App\Forms\Components;

use App\Enums\PaymentMethods;
use Filament\Forms\Components\Field;
use Filament\Forms\Components\Select;

class SelectPaymentMethod extends Select {

    protected function setUp() : void {
        parent::setUp();

        $this->options = PaymentMethods::options()->toArray();
    }

}
