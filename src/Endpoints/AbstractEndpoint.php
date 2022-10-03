<?php

namespace MailerLite\Endpoints;

use MailerLite\Common\HttpLayer;
use MailerLite\Helpers\BuildUri;

abstract class AbstractEndpoint
{
    protected HttpLayer $httpLayer;
    protected array $options;

    public function __construct(HttpLayer $httpLayer, array $options)
    {
        $this->httpLayer = $httpLayer;
        $this->options = $options;
    }

    protected function buildUri(string $path, array $params = []): string
    {
        return (new BuildUri($this->options))->execute($path, $params);
    }
}
