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

    public function test_get_ecommerce_stats()
    {
        $this->automations->getEcommerceStats($this->automationId);

        $request = $this->client->getLastRequest();
        self::assertEquals('GET', $request->getMethod());
        self::assertEquals("/api/automations/{$this->automationId}/monthly-totals/ecommerce", $request->getUri()->getPath());
    }

    public function test_get_email_ecommerce_stats()
    {
        $this->automations->getEmailEcommerceStats($this->automationId, '456', ['shop_id' => '1']);

        $request = $this->client->getLastRequest();
        self::assertEquals('GET', $request->getMethod());
        self::assertEquals("/api/automations/{$this->automationId}/emails/456/ecommerce-overview-stats", $request->getUri()->getPath());
        self::assertEquals('shop_id=1', $request->getUri()->getQuery());
    }

    public function test_get_ecommerce_orders()
    {
        $this->automations->getEcommerceOrders($this->automationId, ['time_frame' => 'all_time']);

        $request = $this->client->getLastRequest();
        self::assertEquals('GET', $request->getMethod());
        self::assertEquals("/api/automations/{$this->automationId}/order-activity", $request->getUri()->getPath());
        self::assertEquals('time_frame=all_time', $request->getUri()->getQuery());
    }
}
