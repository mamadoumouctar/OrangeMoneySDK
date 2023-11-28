<?php

namespace Tm\OrangeMoneySDK\Domain\Entity;

readonly class OmResponseError
{
    private readonly string $type;

    private readonly string $title;

    private readonly string $instance;

    private readonly int $status;

    private readonly int $code;

    private readonly string $detail;

    /** @var array<string, string> */
    private readonly array $violations;

    /** @param array<string, string> $violations */
    private function __construct(string $type, string $title, string $instance, int $status, int $code, string $detail, array $violations = [])
    {
        $this->type = $type;
        $this->title = $title;
        $this->instance = $instance;
        $this->status = $status;
        $this->code = $code;
        $this->detail = $detail;
        $this->violations = $violations;
    }

    /**
     * @param array<string, string> $data
     * @return self
     */
    public static function makeFromArray(array $data): self
    {
        /** @var array<string, string> $violations */
        $violations = $data['violations'] ?? [];
        return new self(
            $data['type'],
            $data['title'],
            $data['instance'],
            (int)$data['status'],
            (int)$data['code'],
            $data['detail'],
            $violations
        );
    }

    public function getType(): string
    {
        return $this->type;
    }

    public function getTitle(): string
    {
        return $this->title;
    }

    public function getInstance(): string
    {
        return $this->instance;
    }

    public function getStatus(): int
    {
        return $this->status;
    }

    public function getCode(): int
    {
        return $this->code;
    }

    public function getDetail(): string
    {
        return $this->detail;
    }

    /**
     * @return array<string>
     */
    public function getViolations(): array
    {
        return $this->violations;
    }
}