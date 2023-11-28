<?php

namespace Tm\OrangeMoneySDK\Http\io;

class HttpClientResponse
{
    private readonly bool $isSuccess;
    private readonly mixed $data;

    /**
     * @param bool $isSuccess
     * @param mixed $data
     */
    public function __construct(bool $isSuccess, mixed $data)
    {
        $this->isSuccess = $isSuccess;
        $this->data = $data;
    }

    public function isSuccess(): bool
    {
        return $this->isSuccess;
    }

    public function getData(): mixed
    {
        return $this->data;
    }
}