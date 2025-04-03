<?php

namespace App\Contracts\Payment;

interface RedirectPayment {

    function callbackUrl(array $params): string;

}