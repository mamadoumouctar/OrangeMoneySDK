<?php

namespace Tm\OrangeMoneySDK\Domain\Entity;

use Tm\OrangeMoneySDK\Domain\Entity\Enum\IdType;
use Tm\OrangeMoneySDK\Domain\Entity\Enum\WalletType;

class Partner
{
    private readonly string $id;

    private readonly IdType $idType;

    private readonly WalletType $walletType;

    public function __construct(string $id, IdType $idType = IdType::CODE, WalletType $walletType = WalletType::PRINCIPAL)
    {
        $this->id = $id;
        $this->idType = $idType;
        $this->walletType = $walletType;
    }

    public function getId(): string
    {
        return $this->id;
    }

    public function getIdType(): IdType
    {
        return $this->idType;
    }

    public function getWalletType(): WalletType
    {
        return $this->walletType;
    }
}
