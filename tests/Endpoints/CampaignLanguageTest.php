<?php

namespace MailerLite\Tests\Endpoints;

use Http\Mock\Client;
use MailerLite\Common\HttpLayer;
use MailerLite\Endpoints\CampaignLanguage;
use MailerLite\Tests\TestCase;
use Psr\Http\Message\ResponseInterface;

class CampaignLanguageTest extends TestCase
{
    protected CampaignLanguage $campaignLanguage;
    protected ResponseInterface $response;

    protected function setUp(): void
    {
        parent::setUp();

        $this->client = new Client();

        $this->campaignLanguage = new CampaignLanguage(new HttpLayer(self::OPTIONS, $this->client), self::OPTIONS);

        $this->response = $this->createMock(ResponseInterface::class);
        $this->response->method('getStatusCode')->willReturn(200);
        $this->client->addResponse($this->response);
    }

    public function test_read_all()
    {
        $this->campaignLanguage->get();

        $request = $this->client->getLastRequest();

        self::assertEquals('GET', $request->getMethod());
        self::assertEquals('/api/campaigns/languages', $request->getUri()->getPath());
    }
}
