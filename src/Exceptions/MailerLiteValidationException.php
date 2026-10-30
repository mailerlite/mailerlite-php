<?php

namespace MailerLite\Exceptions;

use Psr\Http\Message\RequestInterface;
use Psr\Http\Message\ResponseInterface;

class MailerLiteValidationException extends MailerLiteException
{
    protected RequestInterface $request;
    protected ResponseInterface $response;

    /**
     * @var array<string, mixed>
     */
    protected array $errors = [];

    public function __construct(
        RequestInterface $request,
        ResponseInterface $response
    ) {
        $body = self::readBody($response);
        /** @var array<string|int, mixed> $data */
        $data = json_decode($body, true, 512, JSON_THROW_ON_ERROR);
        /** @var string $message */
        $message = $data['message'];

        parent::__construct($message);

        $this->request = $request;
        $this->response = $response;

        if (isset($data['errors']) && is_array($data['errors'])) {
            /** @var array<string, mixed> $errors */
            $errors = $data['errors'];
            $this->errors = $errors;
        }
    }

    public function getResponse(): ResponseInterface
    {
        return $this->response;
    }

    public function getRequest(): RequestInterface
    {
        return $this->request;
    }

    /**
     * @return array<string, mixed>
     */
    public function getErrors(): array
    {
        return $this->errors;
    }
}
