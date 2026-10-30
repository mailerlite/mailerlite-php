<?php

namespace MailerLite\Tests\Endpoints;

use Http\Mock\Client;
use MailerLite\Common\HttpLayer;
use MailerLite\Endpoints\Subscriber;
use MailerLite\Tests\Support\AssertsRequests;
use MailerLite\Tests\TestCase;
use Psr\Http\Message\ResponseInterface;

class SubscriberTest extends TestCase
{
    use AssertsRequests;

    protected Subscriber $subscribers;
    protected ResponseInterface $response;
    protected int $subscriberId;

    protected function setUp(): void
    {
        parent::setUp();

        $this->client = new Client();

        $this->subscribers = new Subscriber(new HttpLayer(self::OPTIONS, $this->client), self::OPTIONS);
        $this->subscriberId = '123';

        $this->response = $this->createMock(ResponseInterface::class);
        $this->response->method('getStatusCode')->willReturn(200);
        $this->client->addResponse($this->response);
    }

    public function test_create()
    {
        $this->subscribers->create([]);

        $request = $this->client->getLastRequest();

        self::assertEquals('POST', $request->getMethod());
        self::assertEquals('/api/subscribers', $request->getUri()->getPath());
    }

    public function test_find()
    {
        $this->subscribers->find($this->subscriberId);

        $request = $this->client->getLastRequest();

        self::assertEquals('GET', $request->getMethod());
        self::assertEquals("/api/subscribers/{$this->subscriberId}", $request->getUri()->getPath());
    }

    public function test_get()
    {
        $this->subscribers->get([]);

        $request = $this->client->getLastRequest();

        self::assertEquals('GET', $request->getMethod());
        self::assertEquals('/api/subscribers', $request->getUri()->getPath());
    }

    public function test_update()
    {
        $this->subscribers->update($this->subscriberId, []);

        $request = $this->client->getLastRequest();

        self::assertEquals('PUT', $request->getMethod());
        self::assertEquals("/api/subscribers/{$this->subscriberId}", $request->getUri()->getPath());
    }

    public function test_delete()
    {
        $this->subscribers->delete($this->subscriberId);

        $request = $this->client->getLastRequest();

        self::assertEquals('DELETE', $request->getMethod());
        self::assertEquals("/api/subscribers/{$this->subscriberId}", $request->getUri()->getPath());
    }

    public function test_forget()
    {
        $this->subscribers->forget($this->subscriberId);

        $request = $this->client->getLastRequest();

        self::assertEquals('POST', $request->getMethod());
        self::assertEquals("/api/subscribers/{$this->subscriberId}/forget", $request->getUri()->getPath());
    }

    public function test_get_ecommerce_stats()
    {
        $this->subscribers->getEcommerceStats($this->subscriberId, ['shop_id' => '1']);

        $request = $this->client->getLastRequest();

        self::assertEquals('GET', $request->getMethod());
        self::assertEquals("/api/subscribers/{$this->subscriberId}/reports/ecommerce", $request->getUri()->getPath());
        self::assertEquals('shop_id=1', $request->getUri()->getQuery());
    }

    public function test_get_ecommerce_orders()
    {
        $this->subscribers->getEcommerceOrders($this->subscriberId, ['shop_id' => '1']);

        $request = $this->client->getLastRequest();

        self::assertEquals('GET', $request->getMethod());
        self::assertEquals("/api/subscribers/{$this->subscriberId}/reports/ecommerce/orders", $request->getUri()->getPath());
        self::assertEquals('shop_id=1', $request->getUri()->getQuery());
    }

    public function test_get_activity_log()
    {
        $this->subscribers->getActivityLog($this->subscriberId, [
            'filter' => ['log_name' => 'campaign_send'],
            'limit' => 10,
            'page' => 2,
        ]);

        $this->assertLastRequest('GET', "/api/subscribers/{$this->subscriberId}/activity-log", [
            'filter' => ['log_name' => 'campaign_send'],
            'limit' => '10',
            'page' => '2',
        ]);
    }

    public function test_find_import()
    {
        $this->subscribers->findImport('456');

        $this->assertLastRequest('GET', '/api/subscribers/import/456');
    }
}
