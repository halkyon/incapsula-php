<?php

namespace Incapsula;

use Exception;
use GuzzleHttp\ClientInterface;
use GuzzleHttp\Psr7\Request;
use Incapsula\Api\IntegrationApi;
use Incapsula\Api\SitesApi;

class Client
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
     * @var ClientInterface
     */
    private $httpClient;

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
     * @return HttpClient
     */
    public function getHttpClient()
    {
        if (!$this->httpClient) {
            return new HttpClient(['timeout' => 20]);
        }

        return $this->httpClient;
    }

    /**
     * @param ClientInterface $client
     */
    public function setHttpClient(ClientInterface $client)
    {
        $this->httpClient = $client;
    }

    /**
     * @return IntegrationApi
     */
    public function integration()
    {
        return new IntegrationApi($this);
    }

    /**
     * @return SitesApi
     */
    public function sites()
    {
        return new SitesApi($this);
    }

    /**
     * @param string $uri
     * @param array  $params
     * @param array  $headers
     *
     * @throws Exception
     *
     * @return array
     */
    public function send($uri, $params = [], $headers = [])
    {
        // apply credentials to all api calls except integration/ips, which doesn't require them.
        if (false === strpos($uri, 'integration/ips')) {
            $params = array_merge($params, [
                'api_id' => $this->apiId,
                'api_key' => $this->apiKey,
            ]);
        }

        $request = new Request('POST', $uri, $headers);
        $response = $this->getHttpClient()->send($request, ['form_params' => $params]);
        $data = json_decode($response->getBody(), true);

        if (null === $data) {
            throw new Exception(sprintf('Could not parse JSON (code: %s)', json_last_error()));
        }
        if (0 !== $data['res']) {
            throw new Exception(sprintf('Bad response: %s (code: %s)', $data['res_message'], $data['res']));
        }

        return $data;
    }
}
