<?php

namespace Tm\OrangeMoneySDK\Http\io;

interface HttpResponseCallbackInterface
{
    public function successHandler(mixed $data): void;

    public function errorHandler(mixed $error): void;
}
