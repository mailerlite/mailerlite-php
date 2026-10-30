<?php

namespace MailerLite\Tests\Endpoints;

use Http\Mock\Client;
use MailerLite\Common\HttpLayer;
use MailerLite\Endpoints\BrandStyle;
use MailerLite\Tests\TestCase;
use Psr\Http\Message\ResponseInterface;

class BrandStyleTest extends TestCase
{
    protected BrandStyle $brandStyles;
    protected ResponseInterface $response;

    protected function setUp(): void
    {
        parent::setUp();

        $this->client = new Client();

        $this->brandStyles = new BrandStyle(new HttpLayer(self::OPTIONS, $this->client), self::OPTIONS);

        $this->response = $this->createMock(ResponseInterface::class);
        $this->response->method('getStatusCode')->willReturn(200);
        $this->client->addResponse($this->response);
    }

    public function test_get()
    {
        $this->brandStyles->get();

        $request = $this->client->getLastRequest();

        self::assertEquals('GET', $request->getMethod());
        self::assertEquals('/api/brand-styles', $request->getUri()->getPath());
    }
}
