<?php

namespace Incapsula;

use GuzzleHttp\Client as GuzzleClient;
use GuzzleHttp\Exception\ConnectException;
use GuzzleHttp\Handler\CurlHandler;
use GuzzleHttp\HandlerStack;
use GuzzleHttp\Middleware;

class HttpClient extends GuzzleClient
{
    private $retryStatusCodes = [500, 502, 503, 504, 429];

    public function __construct(array $config = [])
    {
        $handler = HandlerStack::create(new CurlHandler());
        $handler->push($this->getRetryMiddleware());

        parent::__construct(array_merge($config, ['handler' => $handler]));
    }

    private function getRetryMiddleware()
    {
        return Middleware::retry(
            function ($retries, $request, $response, $exception) {
                if ($retries > 10) {
                    return false;
                }
                if ($exception instanceof ConnectException) {
                    return true;
                }
                if ($response && \in_array($response->getStatusCode(), $this->retryStatusCodes, true)) {
                    return true;
                }

                return false;
            }
        );
    }
}
