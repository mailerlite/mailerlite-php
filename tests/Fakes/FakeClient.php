<?php

namespace MailerLite\Tests\Fakes;

use MailerLite\Http\ClientInterface;
use Psr\Http\Message\RequestInterface;
use Psr\Http\Message\ResponseInterface;

final class FakeClient implements ClientInterface
{
    /** @var ResponseInterface[] */
    public array $queue = [];

    public ?RequestInterface $lastRequest = null;

    public function sendRequest(RequestInterface $request): ResponseInterface
    {
        $this->lastRequest = $request;

        if ($this->queue === []) {
            throw new \RuntimeException('Response queue is empty');
        }
        return array_shift($this->queue);
    }
}
