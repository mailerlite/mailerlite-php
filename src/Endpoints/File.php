<?php

namespace MailerLite\Endpoints;

class File extends AbstractEndpoint
{
    protected string $endpoint = 'files';

    /**
     * @param array<string, mixed> $params
     *
     * @return array<string, mixed>
     */
    public function get(array $params = []): array
    {
        return $this->httpLayer->get(
            $this->buildUri($this->endpoint, $params)
        );
    }
}
