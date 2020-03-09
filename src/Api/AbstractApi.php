<?php

declare(strict_types=1);

namespace Incapsula\Api;

use Incapsula\Client;

abstract class AbstractApi
{
    /**
     * @var Client
     */
    protected $client;

    public function __construct(Client $client)
    {
        $this->client = $client;
    }
}
