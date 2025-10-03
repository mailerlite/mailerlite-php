<?php

namespace MailerLite\Tests\Fakes;

use MailerLite\Http\ClientInterface;
use Psr\Http\Message\RequestInterface;
use Psr\Http\Message\ResponseInterface;

final class FakeClient implements ClientInterface
{
    /** @var ResponseInterface[] */
    public array $queue = [];

    public function sendRequest(RequestInterface $request): ResponseInterface
    {
        if ($this->queue === []) {
            throw new \RuntimeException('Response queue is empty');
        }
        return array_shift($this->queue);
    }
}
