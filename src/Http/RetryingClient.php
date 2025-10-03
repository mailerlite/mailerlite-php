<?php

namespace MailerLite\Http;

use Psr\Http\Message\RequestInterface;
use Psr\Http\Message\ResponseInterface;

final class RetryingClient implements ClientInterface
{
    public function __construct(
        private readonly ClientInterface $inner,
        private readonly int $maxAttempts = 3,
        private readonly int $baseDelayMs = 300
    ) {
    }

    public function sendRequest(RequestInterface $request): ResponseInterface
    {
        $attempt = 0;

        do {
            $attempt++;
            $response = $this->inner->sendRequest($request);
            
            if ($this->shouldRetry($response, $attempt)) {
                $this->waitBeforeRetry($response, $attempt);
                continue;
            }
            
            return $response;
        } while ($attempt < $this->maxAttempts);

        return $response;
    }

    private function shouldRetry(ResponseInterface $response, int $attempt): bool
    {
        if ($attempt >= $this->maxAttempts) {
            return false;
        }
        
        $statusCode = $response->getStatusCode();
        return $statusCode === 429 || $statusCode >= 500;
    }

    private function waitBeforeRetry(ResponseInterface $response, int $attempt): void
    {
        $retryAfter = $response->getHeaderLine('Retry-After');
        
        $delayMs = $this->calculateDelayMs($retryAfter, $attempt);
        usleep($delayMs * 1000);
    }

    private function calculateDelayMs(string $retryAfter, int $attempt): int
    {
        if ($retryAfter !== '' && is_numeric($retryAfter)) {
            return (int) $retryAfter * 1000;
        }
        
        return $this->baseDelayMs * (2 ** ($attempt - 1));
    }
}