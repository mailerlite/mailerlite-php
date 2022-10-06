<?php

namespace MailerLite\Tests\Endpoints;

use Http\Mock\Client;
use MailerLite\Common\HttpLayer;
use MailerLite\Endpoints\Segment;
use MailerLite\Tests\TestCase;
use Psr\Http\Message\ResponseInterface;

class SegmentTest extends TestCase
{
    protected Segment $segments;
    protected ResponseInterface $response;
    protected int $segmentId;

    protected function setUp(): void
    {
        parent::setUp();

        $this->client = new Client();

        $this->segments = new Segment(new HttpLayer(self::OPTIONS, $this->client), self::OPTIONS);
        $this->segmentId = '123';

        $this->response = $this->createMock(ResponseInterface::class);
        $this->response->method('getStatusCode')->willReturn(200);
        $this->client->addResponse($this->response);
    }

    public function test_read_all()
    {
        $this->segments->get([]);

        $request = $this->client->getLastRequest();

        self::assertEquals('GET', $request->getMethod());
        self::assertEquals('/api/segments', $request->getUri()->getPath());
    }

    public function test_update()
    {
        $this->segments->update($this->segmentId, []);

        $request = $this->client->getLastRequest();

        self::assertEquals('PUT', $request->getMethod());
        self::assertEquals("/api/segments/{$this->segmentId}", $request->getUri()->getPath());
    }

    public function test_delete()
    {
        $this->segments->delete($this->segmentId);

        $request = $this->client->getLastRequest();

        self::assertEquals('DELETE', $request->getMethod());
        self::assertEquals("/api/segments/{$this->segmentId}", $request->getUri()->getPath());
    }

    public function test_get_group_subscribers()
    {
        $this->segments->getSubscribers($this->segmentId);

        $request = $this->client->getLastRequest();

        self::assertEquals('GET', $request->getMethod());
        self::assertEquals("/api/segments/{$this->segmentId}/subscribers", $request->getUri()->getPath());
    }
}
