<?php

namespace Tm\OrangeMoneySDK\Http\GetUserProfileInfo;

use Tm\OrangeMoneySDK\Domain\Entity\Amount;
use Tm\OrangeMoneySDK\Domain\Entity\Enum\Unit;

class UserProfileInfo
{
    private readonly string $msisdn;

    private readonly string $userId;

    private readonly string $firstName;

    private readonly string $lastName;

    private readonly string $grade;

    private readonly string $barred;

    private readonly Amount $balance;

    private readonly Amount $frozenBalance;

    private readonly bool $suspended;

    private readonly string $type;

    private function __construct(string $msisdn, string $userId, string $firstName, string $lastName, string $grade, string $barred, Amount $balance, Amount $frozenBalance, bool $suspended, string $type)
    {
        $this->msisdn = $msisdn;
        $this->userId = $userId;
        $this->firstName = $firstName;
        $this->lastName = $lastName;
        $this->grade = $grade;
        $this->barred = $barred;
        $this->balance = $balance;
        $this->frozenBalance = $frozenBalance;
        $this->suspended = $suspended;
        $this->type = $type;
    }

    /**
     * @param array<string, mixed> $data
     * @return self
     */
    public static function fromArray(array $data): self
    {
        return new self(
            $data['msisdn'], $data['userId'], $data['firstName'], $data['lastName'], $data['grade'], $data['barred'],
            new Amount($data['balance']['value'], Unit::from($data['balance']['unit'])),
            new Amount($data['frozenBalance']['value'], Unit::from($data['frozenBalance']['unit'])),
            $data['suspended'] == 'Yes', $data['type']);
    }

    public function getMsisdn(): string
    {
        return $this->msisdn;
    }

    public function getUserId(): string
    {
        return $this->userId;
    }

    public function getFirstName(): string
    {
        return $this->firstName;
    }

    public function getLastName(): string
    {
        return $this->lastName;
    }

    public function getGrade(): string
    {
        return $this->grade;
    }

    public function getBarred(): string
    {
        return $this->barred;
    }

    public function getBalance(): Amount
    {
        return $this->balance;
    }

    public function getFrozenBalance(): Amount
    {
        return $this->frozenBalance;
    }

    public function isSuspended(): bool
    {
        return $this->suspended;
    }

    public function getType(): string
    {
        return $this->type;
    }
}