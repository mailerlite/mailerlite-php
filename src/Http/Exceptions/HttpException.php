<?php

namespace MailerLite\Http\Exceptions;

use RuntimeException;

abstract class HttpException extends RuntimeException
{
    protected int $statusCode;
    protected ?string $responseBody;
    /** @var array<string,string[]> */
    protected array $responseHeaders;

    /**
     * @param array<string,string[]> $responseHeaders
     */
    public function __construct(
        string $message,
        int $statusCode = 0,
        ?string $responseBody = null,
        array $responseHeaders = [],
        ?\Throwable $previous = null
    ) {
        parent::__construct($message, $statusCode, $previous);
        $this->statusCode = $statusCode;
        $this->responseBody = $responseBody;
        $this->responseHeaders = $responseHeaders;
    }

    public function getStatusCode(): int
    {
        return $this->statusCode;
    }

    public function getResponseBody(): ?string
    {
        return $this->responseBody;
    }

    /**
     * @return array<string,string[]>
     */
    public function getResponseHeaders(): array
    {
        return $this->responseHeaders;
    }

    public function getRequestId(?string $headerName = 'X-Request-Id'): ?string
    {
        foreach ($this->responseHeaders as $name => $values) {
            if (strcasecmp($name, (string)$headerName) === 0) {
                return $values[0] ?? null;
            }
        }
        return null;
    }
}
