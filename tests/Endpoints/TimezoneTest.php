<?php

namespace MailerLite\Tests\Endpoints;

use Http\Mock\Client;
use MailerLite\Common\HttpLayer;
use MailerLite\Endpoints\Timezone;
use MailerLite\Tests\TestCase;
use Psr\Http\Message\ResponseInterface;

class TimezoneTest extends TestCase
{
    protected Timezone $timezone;
    protected ResponseInterface $response;

    protected function setUp(): void
    {
        parent::setUp();

        $this->client = new Client();

        $this->timezone = new Timezone(new HttpLayer(self::OPTIONS, $this->client), self::OPTIONS);

        $this->response = $this->createMock(ResponseInterface::class);
        $this->response->method('getStatusCode')->willReturn(200);
        $this->client->addResponse($this->response);
    }

    public function test_read_all()
    {
        $this->timezone->get();

        $request = $this->client->getLastRequest();

        self::assertEquals('GET', $request->getMethod());
        self::assertEquals('/api/timezones', $request->getUri()->getPath());
    }
}
