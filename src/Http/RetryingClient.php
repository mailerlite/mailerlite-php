<?php

namespace MailerLite\Http;

use Psr\Http\Message\RequestInterface;
use Psr\Http\Message\ResponseInterface;

final class RetryingClient implements ClientInterface
{
    public function __construct(
        private ClientInterface $inner,
        private int $maxAttempts = 3,
        private int $baseDelayMs = 300
    ) {
    }

    public function sendRequest(RequestInterface $request): ResponseInterface
    {
        $attempt = 0;
        start:
        $attempt++;
        $response = $this->inner->sendRequest($request);
        $code = $response->getStatusCode();

        if ($attempt < $this->maxAttempts && ($code == 429 || $code >= 500)) {
            $retryAfter = $response->getHeaderLine('Retry-After');
            $delayMs = $retryAfter !== ''
                ? (is_numeric($retryAfter) ? (int)$retryAfter * 1000 : $this->baseDelayMs)
                : ($this->baseDelayMs * (2 ** ($attempt - 1)));

            usleep($delayMs * 1000);
            goto start;
        }
        return $response;
    }
}
