<?php

namespace Tm\OrangeMoneySDK;

trait OrangeMoneyConfig
{
    private const OM_SANDBOX_FQDN = 'api.sandbox.orange-sonatel.com';
    private const OM_PRODUCTION_FQDN = 'api.orange-sonatel.com';

    public function makeFQDN(?string $suffix = ''): string
    {
        return ($this->isProduction ? self::OM_PRODUCTION_FQDN : self::OM_SANDBOX_FQDN) . $suffix;
    }
}
