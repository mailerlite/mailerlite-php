<?php

namespace MailerLite\Tests\Endpoints;

use Http\Mock\Client;
use MailerLite\Common\HttpLayer;
use MailerLite\Endpoints\File;
use MailerLite\Tests\TestCase;
use Psr\Http\Message\ResponseInterface;

class FileTest extends TestCase
{
    protected File $files;
    protected ResponseInterface $response;

    protected function setUp(): void
    {
        parent::setUp();

        $this->client = new Client();

        $this->files = new File(new HttpLayer(self::OPTIONS, $this->client), self::OPTIONS);

        $this->response = $this->createMock(ResponseInterface::class);
        $this->response->method('getStatusCode')->willReturn(200);
        $this->client->addResponse($this->response);
    }

    public function test_read_all()
    {
        $this->files->get();

        $request = $this->client->getLastRequest();

        self::assertEquals('GET', $request->getMethod());
        self::assertEquals('/api/files', $request->getUri()->getPath());
    }
}
