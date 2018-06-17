<?php

namespace Incapsula\Api;

class IntegrationApi extends AbstractApi
{
    private $api_uri = 'https://my.incapsula.com/api/integration/v1';

    /**
     * @return array
     */
    public function ips()
    {
        return $this->client->send(sprintf('%s/ips', $this->api_uri));
    }
}
