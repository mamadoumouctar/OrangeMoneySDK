<?php

namespace Tm\OrangeMoneySDK\Http\Token;

use Tm\OrangeMoneySDK\OrangeMoneyConfig;

readonly class Token
{
    use OrangeMoneyConfig;

    private readonly string $access_token;

    private readonly string $refresh_token;

    private readonly bool $isProduction;

    /**
     * @param string $access_token
     * @param string $refresh_token
     * @param bool $isProduction
     */
    public function __construct(string $access_token, string $refresh_token, bool $isProduction = true)
    {
        $this->access_token = $access_token;
        $this->refresh_token = $refresh_token;
        $this->isProduction = $isProduction;
    }

    public function getAccessToken(): string
    {
        return $this->access_token;
    }

    public function getRefreshToken(): string
    {
        return $this->refresh_token;
    }

    public function isProduction(): bool
    {
        return $this->isProduction;
    }
}
