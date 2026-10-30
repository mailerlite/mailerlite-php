<?php

namespace MailerLite\Endpoints;

use MailerLite\Exceptions\MailerLiteEmptyIdException;
use MailerLite\Exceptions\MailerLiteInvalidArgumentException;
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
            $this->preparePlacement($params),
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
            $this->preparePlacement($params),
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
            $params
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
        $this->assertEtag($etag);

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
            $params,
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
        $this->assertEtag($etag);

        return $this->httpLayer->delete(
            $this->buildUri("{$this->endpoint}/{$emailId}/blocks/{$blockId}"),
            ['etag' => $etag],
            $this->idempotencyHeaders($idempotencyKey)
        );
    }

    /**
     * @param mixed $id
     */
    private function assertId($id, string $name): void
    {
        if (is_int($id)) {
            $id = (string) $id;
        }

        if ($id === '' || $id === null) {
            throw new MailerLiteEmptyIdException("{$name} must not be empty.");
        }

        // The D modifier stops $ from matching before a trailing new line
        if (!is_string($id) || !preg_match('/^[0-9]+$/D', $id)) {
            throw new MailerLiteInvalidIdException("{$name} must contain only the digits 0-9.");
        }
    }

    private function assertEtag(string $etag): void
    {
        if ($etag === '') {
            throw new MailerLiteInvalidArgumentException('etag must not be empty. Send the etag from your last read or write of this email.');
        }
    }

    /**
     * @param array<string, mixed> $params
     *
     * @return array<string, mixed>
     */
    private function preparePlacement(array $params): array
    {
        if (!isset($params['placement']) || !is_array($params['placement'])) {
            return $params;
        }

        $position = $params['placement']['position'] ?? null;

        if ($position === self::POSITION_BEFORE || $position === self::POSITION_AFTER) {
            $this->assertId($params['placement']['block_id'] ?? null, 'placement.block_id');
        } elseif ($position === self::POSITION_START || $position === self::POSITION_END) {
            unset($params['placement']['block_id']);
        }

        return $params;
    }

    /**
     * @return array<string, string>
     */
    private function idempotencyHeaders(?string $idempotencyKey): array
    {
        if ($idempotencyKey === null) {
            return [];
        }

        // No space at either end: HTTP strips it, so the API would see a different key
        if (!preg_match('/^[\x21-\x7E](?:[\x20-\x7E]{0,253}[\x21-\x7E])?$/D', $idempotencyKey)) {
            throw new MailerLiteInvalidArgumentException(
                'idempotencyKey must be 1-255 printable ASCII characters (0x20-0x7E), with no space at the start or the end.'
            );
        }

        return ['Idempotency-Key' => $idempotencyKey];
    }
}
