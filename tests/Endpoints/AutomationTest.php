<?php

namespace MailerLite\Tests\Endpoints;

use Http\Mock\Client;
use MailerLite\Common\HttpLayer;
use MailerLite\Endpoints\Automation;
use MailerLite\Tests\TestCase;
use Psr\Http\Message\ResponseInterface;

class AutomationTest extends TestCase
{
    protected Automation $automations;
    protected ResponseInterface $response;
    protected int $automationId;

    protected function setUp(): void
    {
        parent::setUp();

        $this->client = new Client();

        $this->automations = new Automation(new HttpLayer(self::OPTIONS, $this->client), self::OPTIONS);
        $this->automationId = '123';

        $this->response = $this->createMock(ResponseInterface::class);
        $this->response->method('getStatusCode')->willReturn(200);
        $this->client->addResponse($this->response);
    }

    public function test_read_all()
    {
        $this->automations->get([]);

        $request = $this->client->getLastRequest();

        self::assertEquals('GET', $request->getMethod());
        self::assertEquals('/api/automations', $request->getUri()->getPath());
    }

    public function test_find()
    {
        $this->automations->find($this->automationId);

        $request = $this->client->getLastRequest();
        self::assertEquals('GET', $request->getMethod());
        self::assertEquals("/api/automations/{$this->automationId}", $request->getUri()->getPath());
    }

    public function test_activity()
    {
        $this->automations->activity($this->automationId, []);

        $request = $this->client->getLastRequest();
        self::assertEquals('GET', $request->getMethod());
        self::assertEquals("/api/automations/{$this->automationId}/activity", $request->getUri()->getPath());
    }
}
