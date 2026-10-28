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
}
