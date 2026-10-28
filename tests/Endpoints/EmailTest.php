<?php

namespace MailerLite\Tests\Endpoints;

use Http\Mock\Client;
use MailerLite\Common\HttpLayer;
use MailerLite\Endpoints\Email;
use MailerLite\Tests\TestCase;
use Psr\Http\Message\ResponseInterface;

class EmailTest extends TestCase
{
    protected Email $emails;
    protected ResponseInterface $response;
    protected string $emailId;

    protected function setUp(): void
    {
        parent::setUp();

        $this->client = new Client();

        $this->emails = new Email(new HttpLayer(self::OPTIONS, $this->client), self::OPTIONS);
        $this->emailId = '123';

        $this->response = $this->createMock(ResponseInterface::class);
        $this->response->method('getStatusCode')->willReturn(200);
        $this->client->addResponse($this->response);
    }

    public function test_find()
    {
        $this->emails->find($this->emailId);

        $request = $this->client->getLastRequest();

        self::assertEquals('GET', $request->getMethod());
        self::assertEquals("/api/emails/{$this->emailId}", $request->getUri()->getPath());
    }

    public function test_get_html()
    {
        $this->emails->getHtml($this->emailId);

        $request = $this->client->getLastRequest();

        self::assertEquals('GET', $request->getMethod());
        self::assertEquals("/api/emails/{$this->emailId}/html", $request->getUri()->getPath());
    }

    public function test_find_block()
    {
        $this->emails->findBlock($this->emailId, '456');

        $request = $this->client->getLastRequest();

        self::assertEquals('GET', $request->getMethod());
        self::assertEquals("/api/emails/{$this->emailId}/blocks/456", $request->getUri()->getPath());
    }

    public function test_get_versions()
    {
        $this->emails->getVersions($this->emailId);

        $request = $this->client->getLastRequest();

        self::assertEquals('GET', $request->getMethod());
        self::assertEquals("/api/emails/{$this->emailId}/versions", $request->getUri()->getPath());
    }

    public function test_create_block()
    {
        $this->emails->createBlock($this->emailId, []);

        $request = $this->client->getLastRequest();

        self::assertEquals('POST', $request->getMethod());
        self::assertEquals("/api/emails/{$this->emailId}/blocks", $request->getUri()->getPath());
    }

    public function test_move_block()
    {
        $this->emails->moveBlock($this->emailId, '456', []);

        $request = $this->client->getLastRequest();

        self::assertEquals('POST', $request->getMethod());
        self::assertEquals("/api/emails/{$this->emailId}/blocks/456/move", $request->getUri()->getPath());
    }

    public function test_validate_block()
    {
        $this->emails->validateBlock([]);

        $request = $this->client->getLastRequest();

        self::assertEquals('POST', $request->getMethod());
        self::assertEquals('/api/emails/blocks/validate', $request->getUri()->getPath());
    }

    public function test_validate_settings()
    {
        $this->emails->validateSettings($this->emailId, []);

        $request = $this->client->getLastRequest();

        self::assertEquals('POST', $request->getMethod());
        self::assertEquals("/api/emails/{$this->emailId}/settings/validate", $request->getUri()->getPath());
    }

    public function test_create_template()
    {
        $this->emails->createTemplate($this->emailId, []);

        $request = $this->client->getLastRequest();

        self::assertEquals('POST', $request->getMethod());
        self::assertEquals("/api/emails/{$this->emailId}/templates", $request->getUri()->getPath());
    }

    public function test_create_version()
    {
        $this->emails->createVersion($this->emailId);

        $request = $this->client->getLastRequest();

        self::assertEquals('POST', $request->getMethod());
        self::assertEquals("/api/emails/{$this->emailId}/versions", $request->getUri()->getPath());
    }

    public function test_restore_version()
    {
        $this->emails->restoreVersion($this->emailId, '789');

        $request = $this->client->getLastRequest();

        self::assertEquals('POST', $request->getMethod());
        self::assertEquals("/api/emails/{$this->emailId}/versions/789/restore", $request->getUri()->getPath());
    }
}
