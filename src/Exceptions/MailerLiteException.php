<?php

namespace MailerLite\Exceptions;

use Psr\Http\Message\ResponseInterface;

class MailerLiteException extends \ErrorException
{
    protected static function readBody(ResponseInterface $response): string
    {
        $body = $response->getBody();
        $contents = (string) $body;

        if ($body->isSeekable()) {
            $body->rewind();
        }

        return $contents;
    }
}
