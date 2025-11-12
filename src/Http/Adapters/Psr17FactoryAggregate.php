<?php

namespace MailerLite\Http\Adapters;

use MailerLite\Http\RequestFactoryInterface;
use MailerLite\Http\StreamFactoryInterface;
use Psr\Http\Message\RequestFactoryInterface as Psr17RequestFactory;
use Psr\Http\Message\StreamFactoryInterface as Psr17StreamFactory;
use Psr\Http\Message\RequestInterface;
use Psr\Http\Message\StreamInterface;

final class Psr17FactoryAggregate implements RequestFactoryInterface, StreamFactoryInterface
{
   /** @var Psr17RequestFactory */
    private $requestFactory;

    /** @var Psr17StreamFactory */
    private $streamFactory;

    public function __construct(
        Psr17RequestFactory $requestFactory,
        Psr17StreamFactory $streamFactory
    ) {
        $this->requestFactory = $requestFactory;
        $this->streamFactory  = $streamFactory;
    }

    /**
     * @param array<string, string|string[]> $headers
     */
    public function create(
        string $method,
        string $uri,
        array $headers = [],
        ?string $body = null
    ): RequestInterface {
        $req = $this->requestFactory->createRequest($method, $uri);

        foreach ($headers as $name => $value) {
            $values = is_array($value) ? array_values(array_map('strval', $value)) : [ (string)$value ];
            $req = $req->withHeader($name, $values);
        }

        if ($body !== null) {
            $req = $req->withBody($this->createStream($body));
        }

        return $req;
    }

    public function createStream(string $content): StreamInterface
    {
        return $this->streamFactory->createStream($content);
    }
}
