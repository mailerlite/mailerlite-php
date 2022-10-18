<?php

namespace MailerLite\Tests\Endpoints;

use Http\Mock\Client;
use MailerLite\Common\HttpLayer;
use MailerLite\Endpoints\Form;
use MailerLite\Tests\TestCase;
use Psr\Http\Message\ResponseInterface;

class FormTest extends TestCase
{
    protected Form $forms;
    protected ResponseInterface $response;
    protected int $formId;

    protected function setUp(): void
    {
        parent::setUp();

        $this->client = new Client();

        $this->forms = new Form(new HttpLayer(self::OPTIONS, $this->client), self::OPTIONS);
        $this->formId = '123';

        $this->response = $this->createMock(ResponseInterface::class);
        $this->response->method('getStatusCode')->willReturn(200);
        $this->client->addResponse($this->response);
    }

    public function test_read_all()
    {
        $this->forms->get('popup', []);

        $request = $this->client->getLastRequest();

        self::assertEquals('GET', $request->getMethod());
        self::assertEquals('/api/forms/popup', $request->getUri()->getPath());
    }

    public function test_find()
    {
        $this->forms->find($this->formId);

        $request = $this->client->getLastRequest();
        self::assertEquals('GET', $request->getMethod());
        self::assertEquals("/api/forms/{$this->formId}", $request->getUri()->getPath());
    }

    public function test_update()
    {
        $this->forms->update($this->formId, []);

        $request = $this->client->getLastRequest();

        self::assertEquals('PUT', $request->getMethod());
        self::assertEquals("/api/forms/{$this->formId}", $request->getUri()->getPath());
    }

    public function test_delete()
    {
        $this->forms->delete($this->formId);

        $request = $this->client->getLastRequest();

        self::assertEquals('DELETE', $request->getMethod());
        self::assertEquals("/api/forms/{$this->formId}", $request->getUri()->getPath());
    }

    public function test_get_form_signups()
    {
        $this->forms->getSignups($this->formId);

        $request = $this->client->getLastRequest();

        self::assertEquals('GET', $request->getMethod());
    }
}
