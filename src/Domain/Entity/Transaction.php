<?php

namespace Tm\OrangeMoneySDK\Domain\Entity;

class Transaction
{
    private readonly Partner $partner;

    private readonly Customer $customer;

    private readonly Amount $amount;

    private readonly string $reference;

    /** @var array<string, string> */
    private array $metadata = [];

    private function __construct(Partner $partner, Customer $customer, Amount $amount, string $reference)
    {
        $this->partner = $partner;
        $this->customer = $customer;
        $this->amount = $amount;
        $this->reference = $reference;
    }

    public static function forOneStepPay(string $idPartner, Customer $customer, Amount $amount, string $reference): self
    {
        return new Transaction(
            new Partner($idPartner),
            $customer,
            $amount,
            $reference
        );
    }

    public function getPartner(): Partner
    {
        return $this->partner;
    }

    public function getCustomer(): Customer
    {
        return $this->customer;
    }

    public function getAmount(): Amount
    {
        return $this->amount;
    }

    public function getReference(): string
    {
        return $this->reference;
    }

    /**
     * @return array<string, string>
     */
    public function getMetadata(): array
    {
        return $this->metadata;
    }

    /**
     * @param array<string, string> $metadata
     * @return void
     */
    public function setMetadata(array $metadata): void
    {
        $this->metadata = $metadata;
    }
}
