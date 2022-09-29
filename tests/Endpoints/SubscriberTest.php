<?php

namespace MailerLite\Tests\Endpoints;

use Http\Mock\Client;
use MailerLite\Common\HttpLayer;
use MailerLite\Endpoints\Subscriber;
use MailerLite\Tests\TestCase;
use Psr\Http\Message\ResponseInterface;

class SubscriberTest extends TestCase
{
    protected Subscriber $subscriber;
    protected ResponseInterface $response;
    protected int $subscriberId;

    protected function setUp(): void
    {
        parent::setUp();

        $this->client = new Client();

        $this->subscriber = new Subscriber(new HttpLayer(self::OPTIONS, $this->client), self::OPTIONS);
        $this->subscriberId = '123';

        $this->response = $this->createMock(ResponseInterface::class);
        $this->response->method('getStatusCode')->willReturn(200);
        $this->client->addResponse($this->response);
    }

    public function test_create()
    {
        $this->subscriber->create([]);

        $request = $this->client->getLastRequest();

        self::assertEquals('POST', $request->getMethod());
        self::assertEquals('/api/subscribers', $request->getUri()->getPath());
    }

    public function test_read()
    {
        $this->subscriber->read($this->subscriberId);

        $request = $this->client->getLastRequest();

        self::assertEquals('GET', $request->getMethod());
        self::assertEquals("/api/subscribers/{$this->subscriberId}", $request->getUri()->getPath());
    }

    public function test_read_all()
    {
        $this->subscriber->readAll([]);

        $request = $this->client->getLastRequest();

        self::assertEquals('GET', $request->getMethod());
        self::assertEquals('/api/subscribers', $request->getUri()->getPath());
    }

    public function test_update()
    {
        $this->subscriber->update($this->subscriberId, []);

        $request = $this->client->getLastRequest();

        self::assertEquals('PUT', $request->getMethod());
        self::assertEquals("/api/subscribers/{$this->subscriberId}", $request->getUri()->getPath());
    }

    public function test_delete()
    {
        $this->subscriber->delete($this->subscriberId);

        $request = $this->client->getLastRequest();

        self::assertEquals('DELETE', $request->getMethod());
        self::assertEquals("/api/subscribers/{$this->subscriberId}", $request->getUri()->getPath());
    }
}
