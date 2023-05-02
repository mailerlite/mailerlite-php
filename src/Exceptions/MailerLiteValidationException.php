<?php

namespace MailerLite\Exceptions;

use Psr\Http\Message\RequestInterface;
use Psr\Http\Message\ResponseInterface;

class MailerLiteValidationException extends MailerLiteException
{
    protected RequestInterface $request;
    protected ResponseInterface $response;

    public function __construct(
        RequestInterface $request,
        ResponseInterface $response
    ) {
        $body = $response->getBody()->getContents();
        /** @var array<string|int, mixed> $data */
        $data = json_decode($body, true, 512, JSON_THROW_ON_ERROR);
        /** @var string $message */
        $message = $data['message'];

        parent::__construct($message);

        $this->request = $request;
        $this->response = $response;
    }

    public function getResponse(): ResponseInterface
    {
        return $this->response;
    }

    public function getRequest(): RequestInterface
    {
        return $this->request;
    }
}
