<?php

declare(strict_types=1);

namespace Incapsula\Api;

class IntegrationApi extends AbstractApi
{
    /**
     * @var string
     */
    private $apiUri = 'https://my.incapsula.com/api/integration/v1';

    public function ips(): array
    {
        return $this->client->send(sprintf('%s/ips', $this->apiUri));
    }
}
