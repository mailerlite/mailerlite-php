<?php

namespace MailerLite\Http\Adapters;

use MailerLite\Http\ClientInterface;
use Psr\Http\Client\ClientInterface as Psr18Client;
use Psr\Http\Message\RequestInterface;
use Psr\Http\Message\ResponseInterface;

final class GuzzleClientAdapter implements ClientInterface
{
      /** @var Psr18Client */
    private $client;

    public function __construct(Psr18Client $client)
    {
        $this->client = $client;
    }

    public function sendRequest(RequestInterface $request): ResponseInterface
    {
        return $this->client->sendRequest($request);
    }
}
