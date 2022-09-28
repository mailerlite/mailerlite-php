<?php

namespace MailerLite\Tests\Endpoints;

use Http\Mock\Client;
use MailerLite\Common\HttpLayer;
use MailerLite\Endpoints\Campaign;
use MailerLite\Tests\TestCase;
use Psr\Http\Message\ResponseInterface;

class CampaignTest extends TestCase
{
    protected Campaign $campaign;
    protected ResponseInterface $response;
    protected int $campaignId;

    protected function setUp(): void
    {
        parent::setUp();

        $this->client = new Client();

        $this->campaign = new Campaign(new HttpLayer(self::OPTIONS, $this->client), self::OPTIONS);
        $this->campaignId = '123';

        $this->response = $this->createMock(ResponseInterface::class);
        $this->response->method('getStatusCode')->willReturn(200);
        $this->client->addResponse($this->response);
    }

    public function test_create()
    {
        $this->campaign->create([]);

        $request = $this->client->getLastRequest();

        self::assertEquals('POST', $request->getMethod());
        self::assertEquals('/api/campaigns', $request->getUri()->getPath());
    }

    public function test_read()
    {
        $this->campaign->read($this->campaignId);

        $request = $this->client->getLastRequest();

        self::assertEquals('GET', $request->getMethod());
        self::assertEquals("/api/campaigns/{$this->campaignId}", $request->getUri()->getPath());
    }

    public function test_read_all()
    {
        $this->campaign->readAll([]);

        $request = $this->client->getLastRequest();

        self::assertEquals('GET', $request->getMethod());
        self::assertEquals('/api/campaigns', $request->getUri()->getPath());
    }

    public function test_update()
    {
        $this->campaign->update($this->campaignId, []);

        $request = $this->client->getLastRequest();

        self::assertEquals('PUT', $request->getMethod());
        self::assertEquals("/api/campaigns/{$this->campaignId}", $request->getUri()->getPath());
    }

    public function test_delete()
    {
        $this->campaign->delete($this->campaignId);

        $request = $this->client->getLastRequest();

        self::assertEquals('DELETE', $request->getMethod());
        self::assertEquals("/api/campaigns/{$this->campaignId}", $request->getUri()->getPath());
    }

    public function test_schedule()
    {
        $this->campaign->schedule($this->campaignId, []);

        $request = $this->client->getLastRequest();

        self::assertEquals('POST', $request->getMethod());
        self::assertEquals("/api/campaigns/{$this->campaignId}/schedule", $request->getUri()->getPath());
    }

    public function test_stop()
    {
        $this->campaign->cancel($this->campaignId);

        $request = $this->client->getLastRequest();

        self::assertEquals('POST', $request->getMethod());
        self::assertEquals("/api/campaigns/{$this->campaignId}/cancel", $request->getUri()->getPath());
    }
}
