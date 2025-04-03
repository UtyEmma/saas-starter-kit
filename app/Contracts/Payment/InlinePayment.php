<?php

namespace App\Contracts\Payment;

interface InlinePayment {

    function inline(): array;

}