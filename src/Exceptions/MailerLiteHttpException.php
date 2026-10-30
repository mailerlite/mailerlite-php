<?php

namespace MailerLite\Exceptions;

use Psr\Http\Client\RequestExceptionInterface;
use Psr\Http\Message\RequestInterface;
use Psr\Http\Message\ResponseInterface;

class MailerLiteHttpException extends MailerLiteException implements RequestExceptionInterface
{
    public const VERSION_CONFLICT = 'version_conflict';

    protected RequestInterface $request;
    protected ResponseInterface $response;
    protected ?string $errorCode = null;
    protected ?string $etag = null;

    public function __construct(
        RequestInterface $request,
        ResponseInterface $response
    ) {
        $message = sprintf(
            '[url] %s [http method] %s [status code] %s [reason phrase] %s',
            $request->getRequestTarget(),
            $request->getMethod(),
            $response->getStatusCode(),
            $response->getReasonPhrase()
        );

        $this->request = $request;
        $this->response = $response;

        $data = json_decode(self::readBody($response), true);
        if (is_array($data)) {
            $this->errorCode = is_string($data['code'] ?? null) ? $data['code'] : null;
            $this->etag = is_string($data['etag'] ?? null) || is_int($data['etag'] ?? null)
                ? (string) $data['etag']
                : null;
        }

        parent::__construct($message, $response->getStatusCode());
    }

    public function getResponse(): ResponseInterface
    {
        return $this->response;
    }

    public function getRequest(): RequestInterface
    {
        return $this->request;
    }

    // getCode() is the HTTP status; this is the API's `code` field
    public function getErrorCode(): ?string
    {
        return $this->errorCode;
    }

    public function getEtag(): ?string
    {
        return $this->etag;
    }
}
