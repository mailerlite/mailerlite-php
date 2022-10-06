<?php

namespace MailerLite\Tests\Endpoints;

use Http\Mock\Client;
use MailerLite\Common\HttpLayer;
use MailerLite\Endpoints\Group;
use MailerLite\Tests\TestCase;
use Psr\Http\Message\ResponseInterface;

class GroupTest extends TestCase
{
    protected Group $groups;
    protected ResponseInterface $response;
    protected int $groupId;

    protected function setUp(): void
    {
        parent::setUp();

        $this->client = new Client();

        $this->groups = new Group(new HttpLayer(self::OPTIONS, $this->client), self::OPTIONS);
        $this->groupId = '123';

        $this->response = $this->createMock(ResponseInterface::class);
        $this->response->method('getStatusCode')->willReturn(200);
        $this->client->addResponse($this->response);
    }

    public function test_create()
    {
        $this->groups->create([]);

        $request = $this->client->getLastRequest();

        self::assertEquals('POST', $request->getMethod());
        self::assertEquals('/api/groups', $request->getUri()->getPath());
    }

    public function test_find()
    {
        $this->groups->find($this->groupId);

        $request = $this->client->getLastRequest();

        self::assertEquals('GET', $request->getMethod());
        self::assertEquals("/api/groups/{$this->groupId}", $request->getUri()->getPath());
    }

    public function test_read_all()
    {
        $this->groups->get([]);

        $request = $this->client->getLastRequest();

        self::assertEquals('GET', $request->getMethod());
        self::assertEquals('/api/groups', $request->getUri()->getPath());
    }

    public function test_update()
    {
        $this->groups->update($this->groupId, []);

        $request = $this->client->getLastRequest();

        self::assertEquals('PUT', $request->getMethod());
        self::assertEquals("/api/groups/{$this->groupId}", $request->getUri()->getPath());
    }

    public function test_delete()
    {
        $this->groups->delete($this->groupId);

        $request = $this->client->getLastRequest();

        self::assertEquals('DELETE', $request->getMethod());
        self::assertEquals("/api/groups/{$this->groupId}", $request->getUri()->getPath());
    }

    public function test_get_group_subscribers()
    {
        $this->groups->getSubscribers($this->groupId);

        $request = $this->client->getLastRequest();

        self::assertEquals('GET', $request->getMethod());
        self::assertEquals("/api/groups/{$this->groupId}/subscribers", $request->getUri()->getPath());
    }

    public function test_assign_subscriber_to_group()
    {
        $subscriberId = '4567';

        $this->groups->assignSubscriber($this->groupId, $subscriberId);

        $request = $this->client->getLastRequest();

        self::assertEquals('POST', $request->getMethod());
        self::assertEquals("/api/subscribers/{$subscriberId}/groups/{$this->groupId}", $request->getUri()->getPath());
    }

    public function test_unassign_subscriber_from_group()
    {
        $subscriberId = '4567';

        $this->groups->unAssignSubscriber($this->groupId, $subscriberId);

        $request = $this->client->getLastRequest();
        self::assertEquals('DELETE', $request->getMethod());
        self::assertEquals("/api/subscribers/{$subscriberId}/groups/{$this->groupId}", $request->getUri()->getPath());
    }
}
