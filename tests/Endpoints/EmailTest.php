<?php

namespace MailerLite\Tests\Endpoints;

use GuzzleHttp\Psr7\Response;
use Http\Mock\Client;
use MailerLite\Common\HttpLayer;
use MailerLite\Endpoints\Email;
use MailerLite\Exceptions\MailerLiteEmptyIdException;
use MailerLite\Exceptions\MailerLiteHttpException;
use MailerLite\Exceptions\MailerLiteInvalidArgumentException;
use MailerLite\Exceptions\MailerLiteInvalidIdException;
use MailerLite\Exceptions\MailerLiteValidationException;
use MailerLite\Tests\TestCase;
use Psr\Http\Message\ResponseInterface;

class EmailTest extends TestCase
{
    protected Email $emails;
    protected ResponseInterface $response;
    protected string $emailId;
    protected string $blockId;
    protected array $params;

    protected function setUp(): void
    {
        parent::setUp();

        $this->client = new Client();

        $this->emails = new Email(new HttpLayer(self::OPTIONS, $this->client), self::OPTIONS);
        $this->emailId = '123';
        $this->blockId = '456';
        $this->params = ['name' => 'Test'];

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
        $this->emails->findBlock($this->emailId, $this->blockId);

        $request = $this->client->getLastRequest();

        self::assertEquals('GET', $request->getMethod());
        self::assertEquals("/api/emails/{$this->emailId}/blocks/{$this->blockId}", $request->getUri()->getPath());
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
        $this->emails->createBlock($this->emailId, $this->params);

        $request = $this->client->getLastRequest();

        self::assertEquals('POST', $request->getMethod());
        self::assertEquals("/api/emails/{$this->emailId}/blocks", $request->getUri()->getPath());
        self::assertEquals($this->params, json_decode((string) $request->getBody(), true));
    }

    public function test_move_block()
    {
        $this->emails->moveBlock($this->emailId, $this->blockId, $this->params);

        $request = $this->client->getLastRequest();

        self::assertEquals('POST', $request->getMethod());
        self::assertEquals("/api/emails/{$this->emailId}/blocks/{$this->blockId}/move", $request->getUri()->getPath());
        self::assertEquals($this->params, json_decode((string) $request->getBody(), true));
    }

    public function test_validate_block()
    {
        $this->emails->validateBlock($this->params);

        $request = $this->client->getLastRequest();

        self::assertEquals('POST', $request->getMethod());
        self::assertEquals('/api/emails/blocks/validate', $request->getUri()->getPath());
        self::assertEquals($this->params, json_decode((string) $request->getBody(), true));
    }

    public function test_validate_settings()
    {
        $this->emails->validateSettings($this->emailId, $this->params);

        $request = $this->client->getLastRequest();

        self::assertEquals('POST', $request->getMethod());
        self::assertEquals("/api/emails/{$this->emailId}/settings/validate", $request->getUri()->getPath());
        self::assertEquals($this->params, json_decode((string) $request->getBody(), true));
    }

    public function test_create_template()
    {
        $this->emails->createTemplate($this->emailId, $this->params);

        $request = $this->client->getLastRequest();

        self::assertEquals('POST', $request->getMethod());
        self::assertEquals("/api/emails/{$this->emailId}/templates", $request->getUri()->getPath());
        self::assertEquals($this->params, json_decode((string) $request->getBody(), true));
    }

    public function test_create_version()
    {
        $this->emails->createVersion($this->emailId);

        $request = $this->client->getLastRequest();

        self::assertEquals('POST', $request->getMethod());
        self::assertEquals("/api/emails/{$this->emailId}/versions", $request->getUri()->getPath());
        self::assertSame('', (string) $request->getBody());
    }

    public function test_restore_version()
    {
        $this->emails->restoreVersion($this->emailId, '789', '42');

        $request = $this->client->getLastRequest();

        self::assertEquals('POST', $request->getMethod());
        self::assertEquals("/api/emails/{$this->emailId}/versions/789/restore", $request->getUri()->getPath());
        self::assertEquals(['etag' => '42'], json_decode((string) $request->getBody(), true));
    }

    public function test_update_block()
    {
        $this->emails->updateBlock($this->emailId, $this->blockId, $this->params);

        $request = $this->client->getLastRequest();

        self::assertEquals('PATCH', $request->getMethod());
        self::assertEquals("/api/emails/{$this->emailId}/blocks/{$this->blockId}", $request->getUri()->getPath());
        self::assertEquals($this->params, json_decode((string) $request->getBody(), true));
    }

    public function test_update_settings()
    {
        $this->emails->updateSettings($this->emailId, $this->params);

        $request = $this->client->getLastRequest();

        self::assertEquals('PATCH', $request->getMethod());
        self::assertEquals("/api/emails/{$this->emailId}/settings", $request->getUri()->getPath());
        self::assertEquals($this->params, json_decode((string) $request->getBody(), true));
    }

    public function test_delete_block()
    {
        $this->emails->deleteBlock($this->emailId, $this->blockId, '42');

        $request = $this->client->getLastRequest();

        self::assertEquals('DELETE', $request->getMethod());
        self::assertEquals("/api/emails/{$this->emailId}/blocks/{$this->blockId}", $request->getUri()->getPath());
        self::assertEquals(['etag' => '42'], json_decode((string) $request->getBody(), true));
    }

    public function test_delete_block_and_restore_version_reject_an_empty_etag()
    {
        foreach ([
            fn () => $this->emails->deleteBlock($this->emailId, $this->blockId, ''),
            fn () => $this->emails->restoreVersion($this->emailId, '789', ''),
        ] as $call) {
            try {
                $call();
                self::fail('Expected MailerLiteInvalidArgumentException');
            } catch (MailerLiteInvalidArgumentException $e) {
                self::assertStringContainsString('etag', $e->getMessage());
            }
        }

        self::assertFalse($this->client->getLastRequest());
    }

    public function test_an_empty_id_is_rejected_before_the_request()
    {
        $this->expectException(MailerLiteEmptyIdException::class);

        try {
            $this->emails->find('');
        } finally {
            self::assertFalse($this->client->getLastRequest());
        }
    }

    /**
     * @dataProvider invalidIds
     */
    public function test_an_invalid_id_is_rejected_before_the_request(string $id)
    {
        $this->expectException(MailerLiteInvalidIdException::class);

        try {
            $this->emails->find($id);
        } finally {
            self::assertFalse($this->client->getLastRequest());
        }
    }

    public function invalidIds(): array
    {
        return [
            'path segment' => ['123/html'],
            'encoded slash' => ['123%2Fhtml'],
            'trailing new line' => ["123\n"],
            'letters' => ['abc'],
            'space' => [' 123'],
            'negative' => ['-1'],
        ];
    }

    public function test_every_id_argument_is_checked()
    {
        $calls = [
            fn () => $this->emails->getHtml('x'),
            fn () => $this->emails->findBlock($this->emailId, 'x'),
            fn () => $this->emails->getVersions('x'),
            fn () => $this->emails->createBlock('x', []),
            fn () => $this->emails->moveBlock($this->emailId, 'x', []),
            fn () => $this->emails->validateSettings('x', []),
            fn () => $this->emails->createTemplate('x', []),
            fn () => $this->emails->createVersion('x'),
            fn () => $this->emails->restoreVersion($this->emailId, 'x', '42'),
            fn () => $this->emails->updateBlock($this->emailId, 'x', []),
            fn () => $this->emails->updateSettings('x', []),
            fn () => $this->emails->deleteBlock('x', $this->blockId, '42'),
        ];

        foreach ($calls as $i => $call) {
            try {
                $call();
                self::fail("Call {$i} did not reject the ID");
            } catch (MailerLiteInvalidIdException $e) {
                self::assertTrue(true);
            }
        }

        self::assertFalse($this->client->getLastRequest());
    }

    /**
     * @dataProvider placementWithoutValidBlockId
     */
    public function test_before_and_after_require_a_valid_block_id(array $placement, string $exception)
    {
        $this->expectException($exception);

        $this->emails->moveBlock($this->emailId, $this->blockId, ['placement' => $placement]);
    }

    public function placementWithoutValidBlockId(): array
    {
        return [
            'before, missing' => [['position' => 'before'], MailerLiteEmptyIdException::class],
            'after, empty' => [['position' => 'after', 'block_id' => ''], MailerLiteEmptyIdException::class],
            'after, invalid' => [['position' => 'after', 'block_id' => '57/x'], MailerLiteInvalidIdException::class],
        ];
    }

    public function test_placement_block_id_may_be_an_integer()
    {
        $params = ['placement' => ['position' => Email::POSITION_AFTER, 'block_id' => 57]];

        $this->emails->createBlock($this->emailId, $params);

        self::assertEquals($params, json_decode((string) $this->client->getLastRequest()->getBody(), true));
    }

    public function test_start_and_end_do_not_send_block_id()
    {
        $this->emails->createBlock($this->emailId, [
            'type' => 'divider',
            'placement' => ['position' => Email::POSITION_END, 'block_id' => ''],
        ]);

        $body = json_decode((string) $this->client->getLastRequest()->getBody(), true);

        self::assertSame(['position' => 'end'], $body['placement']);
    }

    public function test_idempotency_key_is_sent_as_a_header()
    {
        $this->emails->deleteBlock($this->emailId, $this->blockId, '42', 'retry-key-1');

        self::assertSame('retry-key-1', $this->client->getLastRequest()->getHeaderLine('Idempotency-Key'));
    }

    public function test_no_idempotency_key_header_without_a_key()
    {
        $this->emails->updateSettings($this->emailId, $this->params);

        self::assertFalse($this->client->getLastRequest()->hasHeader('Idempotency-Key'));
    }

    public function test_every_write_accepts_an_idempotency_key()
    {
        $calls = [
            fn () => $this->emails->createBlock($this->emailId, [], 'k'),
            fn () => $this->emails->updateBlock($this->emailId, $this->blockId, [], 'k'),
            fn () => $this->emails->deleteBlock($this->emailId, $this->blockId, '42', 'k'),
            fn () => $this->emails->moveBlock($this->emailId, $this->blockId, [], 'k'),
            fn () => $this->emails->updateSettings($this->emailId, [], 'k'),
            fn () => $this->emails->createTemplate($this->emailId, [], 'k'),
            fn () => $this->emails->createVersion($this->emailId, 'k'),
            fn () => $this->emails->restoreVersion($this->emailId, '789', '42', 'k'),
        ];

        foreach ($calls as $call) {
            $this->client->addResponse($this->response);
            $call();
            self::assertSame('k', $this->client->getLastRequest()->getHeaderLine('Idempotency-Key'));
        }
    }

    /**
     * @dataProvider invalidIdempotencyKeys
     */
    public function test_an_invalid_idempotency_key_is_rejected_before_the_request(string $key)
    {
        $this->expectException(MailerLiteInvalidArgumentException::class);

        try {
            $this->emails->createVersion($this->emailId, $key);
        } finally {
            self::assertFalse($this->client->getLastRequest());
        }
    }

    public function invalidIdempotencyKeys(): array
    {
        return [
            'empty' => [''],
            'too long' => [str_repeat('a', 256)],
            'leading space' => [' key'],
            'trailing space' => ['key '],
            'new line' => ["key\n"],
            'tab' => ["k\tey"],
            'non-ASCII' => ['kéy'],
        ];
    }

    public function test_valid_idempotency_key_edges_are_accepted()
    {
        foreach (['a', str_repeat('a', 255), 'a b', '~!'] as $key) {
            $this->client->addResponse($this->response);
            $this->emails->createVersion($this->emailId, $key);
            self::assertSame($key, $this->client->getLastRequest()->getHeaderLine('Idempotency-Key'));
        }
    }

    public function test_replayed_response_is_flagged()
    {
        $this->client = new Client();
        $this->emails = new Email(new HttpLayer(self::OPTIONS, $this->client), self::OPTIONS);
        $this->client->addResponse(new Response(200, ['Idempotent-Replayed' => 'True'], ''));
        $this->client->addResponse(new Response(200, [], ''));

        self::assertTrue($this->emails->createVersion($this->emailId, 'k')['is_idempotent_replay']);
        self::assertFalse($this->emails->createVersion($this->emailId, 'k')['is_idempotent_replay']);
    }

    public function test_version_conflict_exposes_code_and_etag()
    {
        $this->client = new Client();
        $this->emails = new Email(new HttpLayer(self::OPTIONS, $this->client), self::OPTIONS);
        $body = '{"message":"Conflict","code":"version_conflict","etag":"43"}';
        $this->client->addResponse(new Response(409, ['Content-Type' => 'application/json'], $body));

        try {
            $this->emails->deleteBlock($this->emailId, $this->blockId, '42');
            self::fail('Expected MailerLiteHttpException');
        } catch (MailerLiteHttpException $e) {
            self::assertSame(409, $e->getCode());
            self::assertSame(MailerLiteHttpException::VERSION_CONFLICT, $e->getErrorCode());
            self::assertSame('43', $e->getEtag());
            self::assertSame($body, $e->getResponse()->getBody()->getContents());
        }
    }

    public function test_conflict_without_code_has_no_error_code()
    {
        $this->client = new Client();
        $this->emails = new Email(new HttpLayer(self::OPTIONS, $this->client), self::OPTIONS);
        $this->client->addResponse(new Response(409, [], '{"message":"The campaign is not a draft."}'));

        try {
            $this->emails->updateSettings($this->emailId, $this->params);
            self::fail('Expected MailerLiteHttpException');
        } catch (MailerLiteHttpException $e) {
            self::assertNull($e->getErrorCode());
            self::assertNull($e->getEtag());
        }
    }

    public function test_validation_exception_exposes_errors_and_keeps_the_body_readable()
    {
        $this->client = new Client();
        $this->emails = new Email(new HttpLayer(self::OPTIONS, $this->client), self::OPTIONS);
        $body = '{"message":"Invalid","errors":{"editor_content.attrs.url":["Rejected."]}}';
        $this->client->addResponse(new Response(422, ['Content-Type' => 'application/json'], $body));

        try {
            $this->emails->createBlock($this->emailId, $this->params);
            self::fail('Expected MailerLiteValidationException');
        } catch (MailerLiteValidationException $e) {
            self::assertSame('Invalid', $e->getMessage());
            self::assertSame(['editor_content.attrs.url' => ['Rejected.']], $e->getErrors());
            self::assertSame($body, $e->getResponse()->getBody()->getContents());
        }
    }
}
