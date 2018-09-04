<?php

namespace Incapsula;

use Exception;
use GuzzleHttp\ClientInterface;
use GuzzleHttp\Psr7\Request;
use Incapsula\Api\accountsApi;
use Incapsula\Api\IntegrationApi;
use Incapsula\Api\SitesApi;
use Incapsula\Credentials\CredentialProvider;
use Incapsula\Credentials\Credentials;

class Client
{
    /**
     * @var Credentials
     */
    private $credentials;

    /**
     * @var ClientInterface
     */
    private $httpClient;

    /**
     * @param array $options
     */
    public function __construct(array $options = [])
    {
        $profile = isset($options['profile']) ? $options['profile'] : null;
        $credentials = isset($options['credentials']) ? $options['credentials'] : null;
        if (null === $credentials) {
            $credentials = CredentialProvider::env();
            if (null === $credentials) {
                $credentials = CredentialProvider::ini($profile);
            }
        }

        $this->credentials = $credentials;
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
     * @return AccountsApi
     */
    public function accounts()
    {
        return new AccountsApi($this);
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
                'api_id' => $this->credentials->getApiId(),
                'api_key' => $this->credentials->getApiKey(),
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
