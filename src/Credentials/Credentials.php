<?php

namespace Incapsula\Credentials;

class Credentials
{
    /**
     * @var null|string
     */
    private $apiId;

    /**
     * @var null|string
     */
    private $apiKey;

    /**
     * @param string $apiId
     * @param string $apiKey
     */
    public function __construct($apiId, $apiKey)
    {
        $this->apiId = $apiId;
        $this->apiKey = $apiKey;
    }

    /**
     * @return string
     */
    public function getApiId()
    {
        return $this->apiId;
    }

    /**
     * @return string
     */
    public function getApiKey()
    {
        return $this->apiKey;
    }
}
