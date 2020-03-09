<?php

declare(strict_types=1);

namespace Incapsula;

use Exception;
use GuzzleHttp\ClientInterface;
use GuzzleHttp\Exception\GuzzleException;
use GuzzleHttp\Psr7\Request;
use Incapsula\Api\AccountsApi;
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

    public function __construct(array $options = [])
    {
        $profile = $options['profile'] ?? null;
        $credentials = $options['credentials'] ?? null;
        if (null === $credentials) {
            $credentials = CredentialProvider::env();
            if (null === $credentials) {
                $credentials = CredentialProvider::ini($profile);
            }
        }

        $this->credentials = $credentials;
    }

    public function getHttpClient(): ClientInterface
    {
        if (null === $this->httpClient) {
            return new HttpClient(['timeout' => 20]);
        }

        return $this->httpClient;
    }

    public function setHttpClient(ClientInterface $client): void
    {
        $this->httpClient = $client;
    }

    public function integration(): IntegrationApi
    {
        return new IntegrationApi($this);
    }

    public function sites(): SitesApi
    {
        return new SitesApi($this);
    }

    public function accounts(): AccountsApi
    {
        return new AccountsApi($this);
    }

    /**
     * @throws Exception
     */
    public function send(string $uri, array $params = [], array $headers = []): array
    {
        $data = $this->sendRaw($uri, $params, $headers);

        if (0 !== $data['res']) {
            throw new Exception(sprintf('Bad response: %s (code: %s)', $data['res_message'], $data['res']));
        }

        return $data;
    }

    /**
     * Sends a request to the Incapsula API and returns the raw response (with no checking or parsing done, beyond
     * ensuring there was at least *some* response). Useful for when the API endpoint does not implement the expected
     * 'res' structure expected by self::send().
     *
     * @throws GuzzleException
     */
    public function sendRaw(string $uri, array $params = [], array $headers = []): array
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
        $data = json_decode((string) $response->getBody(), true);

        if (null === $data) {
            throw new Exception(sprintf('Could not parse JSON (code: %s)', json_last_error()));
        }

        return $data;
    }
}
