<?php

namespace MailerLite\Endpoints;

use MailerLite\Common\HttpLayer;
use MailerLite\Helpers\BuildUri;

abstract class AbstractEndpoint
{
    protected HttpLayer $httpLayer;

    /**
     * @var array<string, mixed>
     */
    protected array $options;

    /**
     * @param array<string, mixed> $options
     */
    public function __construct(HttpLayer $httpLayer, array $options)
    {
        $this->httpLayer = $httpLayer;
        $this->options = $options;
    }

    /**
     * @param array<string, mixed> $params
     */
    protected function buildUri(string $path, array $params = []): string
    {
        return (new BuildUri($this->options))->execute($path, $params);
    }
}
