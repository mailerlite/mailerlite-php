<?php

namespace MailerLite\Tests\Endpoints;

use Http\Mock\Client;
use MailerLite\Common\HttpLayer;
use MailerLite\Endpoints\Webhook;
use MailerLite\Tests\TestCase;
use Psr\Http\Message\ResponseInterface;

class WebhookTest extends TestCase
{
    protected Webhook $webhook;
    protected ResponseInterface $response;
    protected int $webhookId;

    protected function setUp(): void
    {
        parent::setUp();

        $this->client = new Client();

        $this->webhook = new Webhook(new HttpLayer(self::OPTIONS, $this->client), self::OPTIONS);
        $this->webhookId = '123';

        $this->response = $this->createMock(ResponseInterface::class);
        $this->response->method('getStatusCode')->willReturn(200);
        $this->client->addResponse($this->response);
    }

    public function test_create()
    {
        $this->webhook->create([]);

        $request = $this->client->getLastRequest();

        self::assertEquals('POST', $request->getMethod());
        self::assertEquals('/api/webhooks', $request->getUri()->getPath());
    }

    public function test_find()
    {
        $this->webhook->find($this->webhookId);

        $request = $this->client->getLastRequest();

        self::assertEquals('GET', $request->getMethod());
        self::assertEquals("/api/webhooks/$this->webhookId", $request->getUri()->getPath());
    }

    public function test_read_all()
    {
        $this->webhook->get([]);

        $request = $this->client->getLastRequest();

        self::assertEquals('GET', $request->getMethod());
        self::assertEquals('/api/webhooks', $request->getUri()->getPath());
    }

    public function test_update()
    {
        $this->webhook->update($this->webhookId, []);

        $request = $this->client->getLastRequest();

        self::assertEquals('PUT', $request->getMethod());
        self::assertEquals("/api/webhooks/$this->webhookId", $request->getUri()->getPath());
    }

    public function test_delete()
    {
        $this->webhook->delete($this->webhookId);

        $request = $this->client->getLastRequest();

        self::assertEquals('DELETE', $request->getMethod());
        self::assertEquals("/api/webhooks/$this->webhookId", $request->getUri()->getPath());
    }
}
