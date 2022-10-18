<?php

namespace MailerLite\Tests\Endpoints;

use Http\Mock\Client;
use MailerLite\Common\HttpLayer;
use MailerLite\Endpoints\Campaign;
use MailerLite\Tests\TestCase;
use Psr\Http\Message\ResponseInterface;

class CampaignTest extends TestCase
{
    protected Campaign $campaigns;
    protected ResponseInterface $response;
    protected int $campaignId;

    protected function setUp(): void
    {
        parent::setUp();

        $this->client = new Client();

        $this->campaigns = new Campaign(new HttpLayer(self::OPTIONS, $this->client), self::OPTIONS);
        $this->campaignId = '123';

        $this->response = $this->createMock(ResponseInterface::class);
        $this->response->method('getStatusCode')->willReturn(200);
        $this->client->addResponse($this->response);
    }

    public function test_create()
    {
        $this->campaigns->create([]);

        $request = $this->client->getLastRequest();

        self::assertEquals('POST', $request->getMethod());
        self::assertEquals('/api/campaigns', $request->getUri()->getPath());
    }

    public function test_find()
    {
        $this->campaigns->find($this->campaignId);

        $request = $this->client->getLastRequest();

        self::assertEquals('GET', $request->getMethod());
        self::assertEquals("/api/campaigns/{$this->campaignId}", $request->getUri()->getPath());
    }

    public function test_read_all()
    {
        $this->campaigns->get([]);

        $request = $this->client->getLastRequest();

        self::assertEquals('GET', $request->getMethod());
        self::assertEquals('/api/campaigns', $request->getUri()->getPath());
    }

    public function test_update()
    {
        $this->campaigns->update($this->campaignId, []);

        $request = $this->client->getLastRequest();

        self::assertEquals('PUT', $request->getMethod());
        self::assertEquals("/api/campaigns/{$this->campaignId}", $request->getUri()->getPath());
    }

    public function test_delete()
    {
        $this->campaigns->delete($this->campaignId);

        $request = $this->client->getLastRequest();

        self::assertEquals('DELETE', $request->getMethod());
        self::assertEquals("/api/campaigns/{$this->campaignId}", $request->getUri()->getPath());
    }

    public function test_schedule()
    {
        $this->campaigns->schedule($this->campaignId, []);

        $request = $this->client->getLastRequest();

        self::assertEquals('POST', $request->getMethod());
        self::assertEquals("/api/campaigns/{$this->campaignId}/schedule", $request->getUri()->getPath());
    }

    public function test_stop()
    {
        $this->campaigns->cancel($this->campaignId);

        $request = $this->client->getLastRequest();

        self::assertEquals('POST', $request->getMethod());
        self::assertEquals("/api/campaigns/{$this->campaignId}/cancel", $request->getUri()->getPath());
    }

    public function test_get_subscriber_activity()
    {
        $data = [
            'type' => 'opened',
        ];

        $this->campaigns->getSubscriberActivity($this->campaignId, $data);
        $request = $this->client->getLastRequest();

        self::assertEquals('POST', $request->getMethod());
        self::assertEquals("/api/campaigns/{$this->campaignId}/subscriber-activity", $request->getUri()->getPath());
    }
}
