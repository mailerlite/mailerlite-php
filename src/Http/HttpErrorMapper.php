<?php

namespace MailerLite\Http;

use Psr\Http\Message\ResponseInterface;
use MailerLite\Http\Exceptions\Unauthorized;
use MailerLite\Http\Exceptions\Forbidden;
use MailerLite\Http\Exceptions\NotFound;
use MailerLite\Http\Exceptions\TooManyRequests;
use MailerLite\Http\Exceptions\ServerError;

final class HttpErrorMapper
{
    public static function throwIfError(ResponseInterface $r): void
    {
        $code = $r->getStatusCode();
        if ($code < 400) {
            return;
        }

        $body    = (string) $r->getBody();
        $headers = $r->getHeaders();
        $message = $body !== '' ? $body : ('HTTP ' . $code);

        switch ($code) {
            case 401:
                throw new Unauthorized($message, 401, $body, $headers);
            case 403:
                throw new Forbidden($message, 403, $body, $headers);
            case 404:
                throw new NotFound($message, 404, $body, $headers);
            case 429:
                throw new TooManyRequests($message, 429, $body, $headers);
            default:
                if ($code >= 500) {
                    throw new ServerError($message, $code, $body, $headers);
                }

                throw new \RuntimeException($message, $code);
        }
    }
}
