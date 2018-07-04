<?php

namespace Incapsula\Tests;

use GuzzleHttp\Client as HttpClient;
use GuzzleHttp\Handler\MockHandler;
use GuzzleHttp\HandlerStack;
use GuzzleHttp\Middleware;
use GuzzleHttp\Psr7\Response;
use Incapsula\Client;
use Incapsula\Credentials\Credentials;
use PHPUnit\Framework\TestCase;

/**
 * @internal
 * @coversNothing
 */
final class ClientTest extends TestCase
{
    public function testGoodResponse()
    {
        $history = [];
        $httpClient = $this->createHttpClient([
            new Response(200, [], file_get_contents(__DIR__.'/Responses/good_response.json')),
        ], $history);

        $client = new Client(['credentials' => new Credentials('fakeid', 'fakekey')]);
        $client->setHttpClient($httpClient);
        $response = $client->send('https://dummy.incapsula.lan/api/something/v1/foo');
        $request = $history[0]['request'];

        $this->assertCount(1, $history);
        $this->assertSame('application/x-www-form-urlencoded', $request->getHeader('Content-Type')[0]);
        $this->assertSame('api_id=fakeid&api_key=fakekey', (string) $request->getBody());
        $this->assertSame('https://dummy.incapsula.lan/api/something/v1/foo', (string) $request->getUri());

        $this->assertSame(0, $response['res'], 'Good response code');
        $this->assertSame('OK', $response['res_message'], 'Good response message');
    }

    public function testBadResponse()
    {
        $httpClient = $this->createHttpClient([
            new Response(200, [], file_get_contents(__DIR__.'/Responses/bad_response.json')),
        ]);

        $this->expectException('Exception');

        $client = new Client(['credentials' => new Credentials(null, null)]);
        $client->setHttpClient($httpClient);
        $client->send('https://dummy.incapsula.lan/api/something/v1/foo');
    }

    private function createHttpClient(array $responses = [], array &$container = [])
    {
        $history = Middleware::history($container);
        $mock = new MockHandler($responses);
        $handler = HandlerStack::create($mock);
        $handler->push($history);

        return new HttpClient(['handler' => $handler]);
    }
}
