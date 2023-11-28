<?php

namespace Tm\OrangeMoneySDK\Http\Token;

use Tm\OrangeMoneySDK\OrangeMoneyConfig;

readonly class TokenCredential
{
    use OrangeMoneyConfig;

    public string $client_id;
    public string $client_secret;
    public bool $isProduction;

    /**
     * @param string $client_id
     * @param string $client_secret
     * @param bool $isProduction
     */
    public function __construct(string $client_id, string $client_secret, bool $isProduction = true)
    {
        $this->client_id = $client_id;
        $this->client_secret = $client_secret;
        $this->isProduction = $isProduction;
    }
}
