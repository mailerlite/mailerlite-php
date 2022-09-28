<?php

namespace MailerLite\Helpers;

use Http\Client\Common\Plugin;
use Http\Promise\Promise;
use MailerLite\Exceptions\MailerLiteValidationException;
use MailerLite\Exceptions\MailerLiteHttpException;
use Psr\Http\Message\RequestInterface;
use Psr\Http\Message\ResponseInterface;

class HttpErrorHelper implements Plugin
{
    public function handleRequest(RequestInterface $request, callable $next, callable $first): Promise
    {
        $promise = $next($request);

        return $promise->then(function (ResponseInterface $response) use ($request) {
            $code = $response->getStatusCode();

            if ($code >= 200 && $code < 400) {
                return $response;
            }

            if ($code === 422) {
                throw new MailerLiteValidationException($request, $response);
            }

            throw new MailerLiteHttpException($request, $response);
        });
    }
}
