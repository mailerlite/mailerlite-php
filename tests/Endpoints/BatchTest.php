<?php

namespace MailerLite\Tests\Endpoints;

use Http\Mock\Client;
use MailerLite\Common\HttpLayer;
use MailerLite\Endpoints\Batch;
use MailerLite\Tests\TestCase;
use Psr\Http\Message\ResponseInterface;

class BatchTest extends TestCase
{
    protected Batch $batch;
    protected ResponseInterface $response;

    protected function setUp(): void
    {
        parent::setUp();

        $this->client = new Client();

        $this->batch = new Batch(new HttpLayer(self::OPTIONS, $this->client), self::OPTIONS);

        $this->response = $this->createMock(ResponseInterface::class);
        $this->response->method('getStatusCode')->willReturn(200);
        $this->client->addResponse($this->response);
    }

    public function test_send()
    {
        $data = [
            'requests' => [
                [
                    'method' => 'post',
                    'path' => 'api/subscribers',
                    'body' => [
                        'email' => 'new_subscriber@mail.com'
                    ]
                ]
            ]
        ];

        $this->batch->send($data);

        $request = $this->client->getLastRequest();

        self::assertEquals('POST', $request->getMethod());
        self::assertEquals('/api/batch', $request->getUri()->getPath());
    }
}
