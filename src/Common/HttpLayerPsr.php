<?php

namespace MailerLite\Common;

use MailerLite\Http\ClientInterface;
use MailerLite\Http\RequestFactoryInterface;
use MailerLite\Http\HttpErrorMapper;
use Psr\Http\Message\ResponseInterface;

final class HttpLayerPsr
{
    public function __construct(
        private ClientInterface $client,
        private RequestFactoryInterface $requestFactory,
        private string $apiKey,
        private string $baseUrl = 'https://connect.mailerlite.com',
        /** @var array<string,string|string[]> */
        private array $defaultHeaders = [
            'Content-Type' => 'application/json',
            'Accept' => 'application/json',
        ]
    ) {
    }

    /**
     * @param array<string, array<mixed>|bool|float|int|string|null> $query
     */
    public function get(string $path, array $query = []): ResponseInterface
    {
        $uri = rtrim($this->baseUrl, '/') . '/' . ltrim($path, '/');
        if ($query !== []) {
            $uri .= '?' . http_build_query($query, '', '&', PHP_QUERY_RFC3986);
        }
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
        $uri = rtrim($this->baseUrl, '/') . '/' . ltrim($path, '/');
        $body = null;
        if ($payload !== []) {
            $bodyEncoded = json_encode($payload, JSON_UNESCAPED_UNICODE | JSON_THROW_ON_ERROR);
            $body = $bodyEncoded; // string
        }
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
        $uri = rtrim($this->baseUrl, '/') . '/' . ltrim($path, '/');
        $body = null;
        if ($payload !== []) {
            $bodyEncoded = json_encode($payload, JSON_UNESCAPED_UNICODE | JSON_THROW_ON_ERROR);
            $body = $bodyEncoded; // string
        }
        $req = $this->requestFactory->create('PUT', $uri, $this->headers(), $body);
        $res = $this->client->sendRequest($req);
        HttpErrorMapper::throwIfError($res);
        return $res;
    }

    public function delete(string $path): ResponseInterface
    {
        $uri = rtrim($this->baseUrl, '/') . '/' . ltrim($path, '/');
        $req = $this->requestFactory->create('DELETE', $uri, $this->headers());
        $res = $this->client->sendRequest($req);
        HttpErrorMapper::throwIfError($res);
        return $res;
    }

    /**
     * @return array<string,string|string[]>
     */
    private function headers(): array
    {
        return ['Authorization' => 'Bearer ' . $this->apiKey] + $this->defaultHeaders;
    }
}
