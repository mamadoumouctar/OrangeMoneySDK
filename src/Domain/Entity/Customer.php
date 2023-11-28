<?php

namespace Tm\OrangeMoneySDK\Domain\Entity;

use Tm\OrangeMoneySDK\Domain\Entity\Enum\IdType;
use Tm\OrangeMoneySDK\Domain\Entity\Enum\WalletType;

readonly class Customer
{
    private string $id;

    private IdType $idType;

    private readonly WalletType $walletType;

    private readonly string $otp;

    public function __construct(string $id, string $otp = '', IdType $idType = IdType::MSISDN, WalletType $walletType = WalletType::PRINCIPAL)
    {
        $this->id = $id;
        $this->otp = $otp;
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

    public function getOtp(): string
    {
        return $this->otp;
    }
}
