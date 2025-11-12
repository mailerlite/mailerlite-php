<?php

namespace MailerLite\Common;

use MailerLite\Http\ClientInterface;
use MailerLite\Http\RequestFactoryInterface;
use MailerLite\Http\HttpErrorMapper;
use Psr\Http\Message\ResponseInterface;

final class HttpLayerPsr
{
    /** @var ClientInterface */
    private $client;

    /** @var RequestFactoryInterface */
    private $requestFactory;

    /** @var string */
    private $apiKey;

    /** @var string */
    private $baseUrl;

    /** @var array<string,string|string[]> */
    private $defaultHeaders;

    /**
     * @param array<string,string|string[]> $defaultHeaders
     */
    public function __construct(
        ClientInterface $client,
        RequestFactoryInterface $requestFactory,
        string $apiKey,
        string $baseUrl = 'https://connect.mailerlite.com',
        array $defaultHeaders = [
            'Content-Type' => 'application/json',
            'Accept' => 'application/json',
        ]
    ) {
        $this->client = $client;
        $this->requestFactory = $requestFactory;
        $this->apiKey = $apiKey;
        $this->baseUrl = $baseUrl;
        $this->defaultHeaders = $defaultHeaders;
    }

    /**
     * @param array<string, array<mixed>|bool|float|int|string|null> $query
     */
    public function get(string $path, array $query = []): ResponseInterface
    {
        $uri = $this->buildUri($path, $query);
        $req = $this->requestFactory->create('GET', $uri, $this->headers());
        $res = $this->client->sendRequest($req);
        HttpErrorMapper::throwIfError($res);
        return $res;
    }

    /**
     * @param array<mixed> $payload
     */
    public function post(string $path, array $payload = []): ResponseInterface
    {
        $uri = $this->buildUri($path);
        $body = $this->encodeJsonBody($payload);
        $req = $this->requestFactory->create('POST', $uri, $this->headers(), $body);
        $res = $this->client->sendRequest($req);
        HttpErrorMapper::throwIfError($res);
        return $res;
    }

    /**
     * @param array<mixed> $payload
     */
    public function put(string $path, array $payload = []): ResponseInterface
    {
        $uri = $this->buildUri($path);
        $body = $this->encodeJsonBody($payload);
        $req = $this->requestFactory->create('PUT', $uri, $this->headers(), $body);
        $res = $this->client->sendRequest($req);
        HttpErrorMapper::throwIfError($res);
        return $res;
    }

    public function delete(string $path): ResponseInterface
    {
        $uri = $this->buildUri($path);
        $req = $this->requestFactory->create('DELETE', $uri, $this->headers());
        $res = $this->client->sendRequest($req);
        HttpErrorMapper::throwIfError($res);
        return $res;
    }

    /**
     * @param array<string, array<mixed>|bool|float|int|string|null> $query
     */
    private function buildUri(string $path, array $query = []): string
    {
        $uri = rtrim($this->baseUrl, '/') . '/' . ltrim($path, '/');
        if ($query !== []) {
            $uri .= '?' . http_build_query($query, '', '&', PHP_QUERY_RFC3986);
        }
        return $uri;
    }

    /**
     * @param array<mixed> $payload
     */
    private function encodeJsonBody(array $payload): ?string
    {
        if ($payload === []) {
            return null;
        }
        return json_encode($payload, JSON_UNESCAPED_UNICODE | JSON_THROW_ON_ERROR);
    }

    /**
     * @return array<string,string|string[]>
     */
    private function headers(): array
    {
        return ['Authorization' => 'Bearer ' . $this->apiKey] + $this->defaultHeaders;
    }
}