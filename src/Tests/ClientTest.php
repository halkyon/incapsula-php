<?php

declare(strict_types=1);

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
 * @coversDefaultClass \Incapsula\Client
 */
final class ClientTest extends TestCase
{
    /**
     * @covers ::send
     */
    public function testGoodResponse(): void
    {
        $history = [];
        $httpClient = $this->createHttpClient([
            new Response(200, [], file_get_contents(__DIR__.'/Responses/good_response.json')),
        ], $history);

        $client = $this->createClient();
        $client->setHttpClient($httpClient);
        $response = $client->send('https://dummy.incapsula.lan/api/something/v1/foo');
        $request = $history[0]['request'];

        static::assertCount(1, $history);
        static::assertSame('application/x-www-form-urlencoded', $request->getHeader('Content-Type')[0]);
        static::assertSame('api_id=fakeid&api_key=fakekey', (string) $request->getBody());
        static::assertSame('https://dummy.incapsula.lan/api/something/v1/foo', (string) $request->getUri());

        static::assertSame(0, $response['res'], 'Good response code');
        static::assertSame('OK', $response['res_message'], 'Good response message');
    }

    /**
     * @covers ::send
     */
    public function testBadResponse(): void
    {
        $httpClient = $this->createHttpClient([
            new Response(200, [], file_get_contents(__DIR__.'/Responses/bad_response.json')),
        ]);

        $this->expectException('Exception');

        $client = $this->createClient();
        $client->setHttpClient($httpClient);
        $client->send('https://dummy.incapsula.lan/api/something/v1/foo');
    }

    private function createClient(): Client
    {
        return new Client(['credentials' => new Credentials('fakeid', 'fakekey')]);
    }

    /**
     * @param Response[] $responses
     */
    private function createHttpClient(array $responses = [], array &$container = []): HttpClient
    {
        $history = Middleware::history($container);
        $mock = new MockHandler($responses);
        $handler = HandlerStack::create($mock);
        $handler->push($history);

        return new HttpClient(['handler' => $handler]);
    }
}
