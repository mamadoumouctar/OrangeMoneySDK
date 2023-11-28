<?php

namespace Tm\OrangeMoneySDK\Test\Adapter;

use Tm\OrangeMoneySDK\Http\io\HttpResponseCallbackInterface;

class CallbackAdapter implements HttpResponseCallbackInterface
{
    private $successHandler;
    private $errorHandler;
    public function __construct($successHandler, $errorHandler)
    {
        $this->successHandler = $successHandler;
        $this->errorHandler = $errorHandler;
    }

    public function successHandler(mixed $data): void
    {
        call_user_func($this->successHandler, $data);
    }

    public function errorHandler(mixed $error): void
    {
        call_user_func($this->errorHandler, $error);
    }
}
