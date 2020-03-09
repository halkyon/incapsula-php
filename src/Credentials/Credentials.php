<?php

declare(strict_types=1);

namespace Incapsula\Credentials;

class Credentials
{
    /**
     * @var string
     */
    private $apiId;

    /**
     * @var string
     */
    private $apiKey;

    public function __construct(string $apiId, string $apiKey)
    {
        $this->apiId = $apiId;
        $this->apiKey = $apiKey;
    }

    public function getApiId(): string
    {
        return $this->apiId;
    }

    public function getApiKey(): string
    {
        return $this->apiKey;
    }
}
