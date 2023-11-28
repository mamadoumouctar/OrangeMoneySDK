<?php

namespace Tm\OrangeMoneySDK\Http\PublicKey;

readonly class PublicKey
{
    private string $keyId;
    private string $keyType;
    private int $keySize;
    private string $key;

    /**
     * @param string $keyId
     * @param string $keyType
     * @param int $keySize
     * @param string $key
     */
    public function __construct(string $key, string $keyId = '', string $keyType = '', int $keySize = 0)
    {
        $this->keyId = $keyId;
        $this->keyType = $keyType;
        $this->keySize = $keySize;
        $this->key = $key;
    }

    public function getKeyId(): string
    {
        return $this->keyId;
    }

    public function getKeyType(): string
    {
        return $this->keyType;
    }

    public function getKeySize(): int
    {
        return $this->keySize;
    }

    public function getKey(): string
    {
        return $this->key;
    }
}
