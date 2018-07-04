<?php

namespace Incapsula\Tests;

use GuzzleHttp\Client as GuzzleClient;
use GuzzleHttp\Handler\MockHandler;
use GuzzleHttp\HandlerStack;
use GuzzleHttp\Middleware;
use GuzzleHttp\Psr7\Response;
use Incapsula\Client as IncapsulaClient;
use Incapsula\Credentials\Credentials;
use PHPUnit\Framework\TestCase;

/**
 * @internal
 * @coversNothing
 */
final class ClientTest extends TestCase
{
    public function testBadResponse()
    {
        $httpClient = $this->createHttpClient([
            new Response(200, [], file_get_contents(__DIR__.'/Responses/bad_response.json')),
        ]);

        $this->expectException('Exception');

        $client = new IncapsulaClient(['credentials' => new Credentials(null, null)]);
        $client->setHttpClient($httpClient);
        $client->send('https://dummy.incapsula.lan/api/something/v1/foo');
    }

    private function createHttpClient(array $responses = [], array &$container = [])
    {
        $history = Middleware::history($container);
        $mock = new MockHandler($responses);
        $handler = HandlerStack::create($mock);
        $handler->push($history);

        return new GuzzleClient(['handler' => $handler]);
    }
}
