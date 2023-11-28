<?php

namespace Tm\OrangeMoneySDK\Http\OneStepPay;

use Tm\OrangeMoneySDK\Domain\Entity\Enum\PaiementResponseStatus;

readonly class OneStepPayResponse
{
    private readonly string $reference;

    private readonly string $transactionId;

    private readonly PaiementResponseStatus $status;

    private readonly string $description;

    public function __construct(string $reference, string $transactionId, PaiementResponseStatus $status, string $description)
    {
        $this->reference = $reference;
        $this->transactionId = $transactionId;
        $this->status = $status;
        $this->description = $description;
    }

    public function getReference(): string
    {
        return $this->reference;
    }

    public function getTransactionId(): string
    {
        return $this->transactionId;
    }

    public function getStatus(): PaiementResponseStatus
    {
        return $this->status;
    }

    public function getDescription(): string
    {
        return $this->description;
    }
}