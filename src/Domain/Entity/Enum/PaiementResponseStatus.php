<?php

namespace Tm\OrangeMoneySDK\Domain\Entity\Enum;

enum PaiementResponseStatus: string
{
    case SUCCESS = 'SUCCESS';
    case FAILED = 'FAILED';
}
