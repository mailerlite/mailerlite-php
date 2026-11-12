<?php

namespace MailerLite\Endpoints;

use MailerLite\Exceptions\MailerLiteEmptyIdException;
use MailerLite\Exceptions\MailerLiteInvalidIdException;

class Email extends AbstractEndpoint
{
    public const POSITION_START = 'start';
    public const POSITION_END = 'end';
    public const POSITION_BEFORE = 'before';
    public const POSITION_AFTER = 'after';

    public const BLOCK_TYPE_BLOCKQUOTE = 'blockquote';
    public const BLOCK_TYPE_BULLETED_LIST = 'bulleted_list';
    public const BLOCK_TYPE_BUTTON = 'button';
    public const BLOCK_TYPE_CODE = 'code';
    public const BLOCK_TYPE_DIVIDER = 'divider';
    public const BLOCK_TYPE_HEADING = 'heading';
    public const BLOCK_TYPE_IMAGE = 'image';
    public const BLOCK_TYPE_NUMBERED_LIST = 'numbered_list';
    public const BLOCK_TYPE_PARAGRAPH = 'paragraph';
    public const BLOCK_TYPE_VIDEO = 'video';
    // Read-only: returned by reads, cannot be created
    public const BLOCK_TYPE_SOCIAL_ICON = 'social_icon';
    public const BLOCK_TYPE_TITLE = 'title';

    public const BRAND_MODE_ACCOUNT = 'account';
    public const BRAND_MODE_TEMPLATE = 'template';
    public const BRAND_MODE_CUSTOM = 'custom';

    protected string $endpoint = 'emails';

    /**
     * @return array<string, mixed>
     */
    public function find(string $emailId): array
    {
        $this->assertId($emailId, 'emailId');

        return $this->httpLayer->get(
            $this->buildUri($this->endpoint) . "/{$emailId}"
        );
    }

    /**
     * @return array<string, mixed>
     */
    public function getHtml(string $emailId): array
    {
        $this->assertId($emailId, 'emailId');

        return $this->httpLayer->get(
            $this->buildUri($this->endpoint) . "/{$emailId}/html"
        );
    }

    /**
     * @return array<string, mixed>
     */
    public function findBlock(string $emailId, string $blockId): array
    {
        $this->assertId($emailId, 'emailId');
        $this->assertId($blockId, 'blockId');

        return $this->httpLayer->get(
            $this->buildUri("{$this->endpoint}/{$emailId}/blocks/{$blockId}")
        );
    }

    /**
     * @param array<string, mixed> $params
     *
     * @return array<string, mixed>
     */
    public function getVersions(string $emailId, array $params = []): array
    {
        $this->assertId($emailId, 'emailId');

        return $this->httpLayer->get(
            $this->buildUri("{$this->endpoint}/{$emailId}/versions", $params)
        );
    }

    /**
     * @param array<string, mixed> $params
     *
     * @return array<string, mixed>
     */
    public function createBlock(string $emailId, array $params, ?string $idempotencyKey = null): array
    {
        $this->assertId($emailId, 'emailId');

        return $this->httpLayer->post(
            $this->buildUri("{$this->endpoint}/{$emailId}/blocks"),
            $this->withEditorContentObjects($params),
            $this->idempotencyHeaders($idempotencyKey)
        );
    }

    /**
     * @param array<string, mixed> $params
     *
     * @return array<string, mixed>
     */
    public function moveBlock(string $emailId, string $blockId, array $params, ?string $idempotencyKey = null): array
    {
        $this->assertId($emailId, 'emailId');
        $this->assertId($blockId, 'blockId');

        return $this->httpLayer->post(
            $this->buildUri("{$this->endpoint}/{$emailId}/blocks/{$blockId}/move"),
            $params,
            $this->idempotencyHeaders($idempotencyKey)
        );
    }

    /**
     * @param array<string, mixed> $params
     *
     * @return array<string, mixed>
     */
    public function validateBlock(array $params): array
    {
        return $this->httpLayer->post(
            $this->buildUri("{$this->endpoint}/blocks/validate"),
            $this->withEditorContentObjects($params)
        );
    }

    /**
     * @param array<string, mixed> $params
     *
     * @return array<string, mixed>
     */
    public function validateSettings(string $emailId, array $params): array
    {
        $this->assertId($emailId, 'emailId');

        return $this->httpLayer->post(
            $this->buildUri("{$this->endpoint}/{$emailId}/settings/validate"),
            $params
        );
    }

    /**
     * @param array<string, mixed> $params
     *
     * @return array<string, mixed>
     */
    public function createTemplate(string $emailId, array $params, ?string $idempotencyKey = null): array
    {
        $this->assertId($emailId, 'emailId');

        return $this->httpLayer->post(
            $this->buildUri("{$this->endpoint}/{$emailId}/templates"),
            $params,
            $this->idempotencyHeaders($idempotencyKey)
        );
    }

    /**
     * @return array<string, mixed>
     */
    public function createVersion(string $emailId, ?string $idempotencyKey = null): array
    {
        $this->assertId($emailId, 'emailId');

        return $this->httpLayer->post(
            $this->buildUri("{$this->endpoint}/{$emailId}/versions"),
            null,
            $this->idempotencyHeaders($idempotencyKey)
        );
    }

    /**
     * @return array<string, mixed>
     */
    public function restoreVersion(
        string $emailId,
        string $versionId,
        string $etag,
        ?string $idempotencyKey = null
    ): array {
        $this->assertId($emailId, 'emailId');
        $this->assertId($versionId, 'versionId');

        return $this->httpLayer->post(
            $this->buildUri("{$this->endpoint}/{$emailId}/versions/{$versionId}/restore"),
            ['etag' => $etag],
            $this->idempotencyHeaders($idempotencyKey)
        );
    }

    /**
     * @param array<string, mixed> $params
     *
     * @return array<string, mixed>
     */
    public function updateBlock(string $emailId, string $blockId, array $params, ?string $idempotencyKey = null): array
    {
        $this->assertId($emailId, 'emailId');
        $this->assertId($blockId, 'blockId');

        return $this->httpLayer->patch(
            $this->buildUri("{$this->endpoint}/{$emailId}/blocks/{$blockId}"),
            $this->withEditorContentObjects($params),
            $this->idempotencyHeaders($idempotencyKey)
        );
    }

    /**
     * @param array<string, mixed> $params
     *
     * @return array<string, mixed>
     */
    public function updateSettings(string $emailId, array $params, ?string $idempotencyKey = null): array
    {
        $this->assertId($emailId, 'emailId');

        return $this->httpLayer->patch(
            $this->buildUri("{$this->endpoint}/{$emailId}/settings"),
            $params,
            $this->idempotencyHeaders($idempotencyKey)
        );
    }

    /**
     * @return array<string, mixed>
     */
    public function deleteBlock(string $emailId, string $blockId, string $etag, ?string $idempotencyKey = null): array
    {
        $this->assertId($emailId, 'emailId');
        $this->assertId($blockId, 'blockId');

        return $this->httpLayer->delete(
            $this->buildUri("{$this->endpoint}/{$emailId}/blocks/{$blockId}"),
            ['etag' => $etag],
            $this->idempotencyHeaders($idempotencyKey)
        );
    }

    /**
     * "123/html" would reach a different endpoint, so path IDs are checked before the request.
     */
    private function assertId(string $id, string $name): void
    {
        if ($id === '') {
            throw new MailerLiteEmptyIdException("{$name} must not be empty.");
        }

        // The D modifier stops $ from matching before a trailing new line
        if (!preg_match('/^[0-9]+$/D', $id)) {
            throw new MailerLiteInvalidIdException("{$name} must contain only the digits 0-9.");
        }
    }

    /**
     * The SDK decodes JSON objects into PHP arrays, so an empty object `{}` in a block that you read
     * comes back as `[]`. JSON-encoded, `[]` is a list, and the API refuses a list where it needs an
     * object. This sends an empty `editor_content` and every empty `attrs` and `mobile` as `{}`.
     *
     * @param array<string, mixed> $params
     *
     * @return array<string, mixed>
     */
    private function withEditorContentObjects(array $params): array
    {
        if (array_key_exists('editor_content', $params)) {
            $params['editor_content'] = $this->withEmptyObjects($params['editor_content']);
        }

        return $params;
    }

    /**
     * @param mixed $node
     *
     * @return mixed
     */
    private function withEmptyObjects($node)
    {
        if (!is_array($node)) {
            return $node;
        }

        if ($node === []) {
            return new \stdClass();
        }

        foreach (['attrs', 'mobile'] as $key) {
            if (isset($node[$key]) && $node[$key] === []) {
                $node[$key] = new \stdClass();
            }
        }

        foreach (['content', 'marks'] as $key) {
            if (isset($node[$key]) && is_array($node[$key])) {
                $node[$key] = array_map(fn ($child) => $this->withEmptyObjects($child), $node[$key]);
            }
        }

        return $node;
    }

    /**
     * @return array<string, string>
     */
    private function idempotencyHeaders(?string $idempotencyKey): array
    {
        if ($idempotencyKey === null) {
            return [];
        }

        return ['Idempotency-Key' => $idempotencyKey];
    }
}
