<?php

namespace MailerLite\Endpoints;

class Email extends AbstractEndpoint
{
    protected string $endpoint = 'emails';

    /**
     * @return array<string, mixed>
     */
    public function find(string $emailId): array
    {
        return $this->httpLayer->get(
            $this->buildUri($this->endpoint) . "/{$emailId}"
        );
    }

    /**
     * @return array<string, mixed>
     */
    public function getHtml(string $emailId): array
    {
        return $this->httpLayer->get(
            $this->buildUri($this->endpoint) . "/{$emailId}/html"
        );
    }

    /**
     * @return array<string, mixed>
     */
    public function findBlock(string $emailId, string $blockId): array
    {
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
        return $this->httpLayer->get(
            $this->buildUri("{$this->endpoint}/{$emailId}/versions", $params)
        );
    }

    /**
     * @param array<string, mixed> $params
     *
     * @return array<string, mixed>
     */
    public function createBlock(string $emailId, array $params): array
    {
        return $this->httpLayer->post(
            $this->buildUri("{$this->endpoint}/{$emailId}/blocks"),
            $params
        );
    }

    /**
     * @param array<string, mixed> $params
     *
     * @return array<string, mixed>
     */
    public function moveBlock(string $emailId, string $blockId, array $params): array
    {
        return $this->httpLayer->post(
            $this->buildUri("{$this->endpoint}/{$emailId}/blocks/{$blockId}/move"),
            $params
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
    public function createTemplate(string $emailId, array $params): array
    {
        return $this->httpLayer->post(
            $this->buildUri("{$this->endpoint}/{$emailId}/templates"),
            $params
        );
    }

    /**
     * @param array<string, mixed> $params
     *
     * @return array<string, mixed>
     */
    public function createVersion(string $emailId, array $params = []): array
    {
        return $this->httpLayer->post(
            $this->buildUri("{$this->endpoint}/{$emailId}/versions"),
            $params
        );
    }

    /**
     * @return array<string, mixed>
     */
    public function restoreVersion(string $emailId, string $versionId): array
    {
        return $this->httpLayer->post(
            $this->buildUri("{$this->endpoint}/{$emailId}/versions/{$versionId}/restore"),
            []
        );
    }

    /**
     * @return array<string, mixed>
     */
    public function deleteBlock(string $emailId, string $blockId): array
    {
        return $this->httpLayer->delete(
            $this->buildUri("{$this->endpoint}/{$emailId}/blocks/{$blockId}")
        );
    }
}
