<?php

namespace MailerLite\Common;

use MailerLite\Exceptions\MailerLiteException;
use Psr\Http\Message\ResponseInterface;

final class HttpLayerPsrBridge extends HttpLayer
{
    /** @var HttpLayerPsr */
    private $psrLayer;

    public function __construct(HttpLayerPsr $psrLayer)
    {
        $this->psrLayer = $psrLayer;
    }

    /** @return array{status_code:int,headers:array<string,string[]>,body:mixed,response:ResponseInterface} */
    public function get(string $uri, array $body = []): array
    {
        /** @var array<string, array<mixed>|bool|float|int|string|null> $query */
        $query = $this->sanitizeQuery($body);
        return $this->executeAndConvert(fn () => $this->psrLayer->get($uri, $query));
    }

    /** @return array{status_code:int,headers:array<string,string[]>,body:mixed,response:ResponseInterface} */
    public function post(string $uri, array $body = []): array
    {
        return $this->executeAndConvert(fn () => $this->psrLayer->post($uri, $body));
    }

    /** @return array{status_code:int,headers:array<string,string[]>,body:mixed,response:ResponseInterface} */
    public function put(string $uri, array $body): array
    {
        return $this->executeAndConvert(fn () => $this->psrLayer->put($uri, $body));
    }

    /** @return array{status_code:int,headers:array<string,string[]>,body:mixed,response:ResponseInterface} */
    public function delete(string $uri, array $body = []): array
    {
        // Note: $body parameter is ignored for DELETE requests as per HTTP semantics
        return $this->executeAndConvert(fn () => $this->psrLayer->delete($uri));
    }

    /** @return array{status_code:int,headers:array<string,string[]>,body:mixed,response:ResponseInterface} */
    public function request(string $method, string $uri, string $body = ''): array
    {
        $method = strtoupper($method);
        $decodedBody = $this->decodeJsonBody($body);

        switch ($method) {
            case 'GET':
                return $this->get($uri, []);
            case 'POST':
                return $this->post($uri, $decodedBody);
            case 'PUT':
                return $this->put($uri, $decodedBody);
            case 'DELETE':
                return $this->delete($uri, []);
            default:
                throw new MailerLiteException("Unsupported HTTP method: {$method}");
        }
    }

    /**
     * Execute PSR layer method and convert response to compatibility format
     * @param callable(): ResponseInterface $psrMethod
     * @return array{status_code:int,headers:array<string,string[]>,body:mixed,response:ResponseInterface}
     */
    private function executeAndConvert(callable $psrMethod): array
    {
        $response = $psrMethod();
        return $this->compatResponse($response);
    }

    /**
     * @return array<mixed>
     */
    private function decodeJsonBody(string $body): array
    {
        if ($body === '') {
            return [];
        }
        $decoded = json_decode($body, true);
        return $decoded !== null ? (array) $decoded : [];
    }

    /**
     * @param  array<string, mixed> $in
     * @return array<string, array<mixed>|bool|float|int|string|null>
     */
    private function sanitizeQuery(array $in): array
    {
        /** @var array<string, array<mixed>|bool|float|int|string|null> $out */
        $out = [];
        foreach ($in as $k => $v) {
            if (is_array($v)) {
                $out[$k] = array_map(
                    /** @return array<mixed>|bool|float|int|string|null */
                    static function ($x) {
                        if (is_bool($x) || is_int($x) || is_float($x) || is_string($x) || $x === null) {
                            return $x;
                        }
                        if (is_object($x) && method_exists($x, '__toString')) {
                            return (string) $x;
                        }
                        $json = json_encode($x);
                        return $json !== false ? $json : get_debug_type($x);
                    },
                    $v
                );
            } elseif (is_bool($v) || is_int($v) || is_float($v) || is_string($v) || $v === null) {
                $out[$k] = $v;
            } else {
                if (is_object($v) && method_exists($v, '__toString')) {
                    $out[$k] = (string) $v;
                } else {
                    $json = json_encode($v);
                    $out[$k] = $json !== false ? $json : get_debug_type($v);
                }
            }
        }
        return $out;
    }

    /**
     * @return array{status_code:int,headers:array<string,string[]>,body:mixed,response:ResponseInterface}
     */
    private function compatResponse(ResponseInterface $response): array
    {
        $headers    = $response->getHeaders();
        $statusCode = $response->getStatusCode();

        $contentTypes = $response->getHeader('Content-Type');
        $contentType  = $response->hasHeader('Content-Type') ? (string) reset($contentTypes) : null;

        switch (true) {
            case $contentType !== null && stripos($contentType, 'application/json') === 0:
                $bodyStr = (string) $response->getBody();
                $body = $bodyStr !== '' ? json_decode($bodyStr, true) : null;
                break;
            default:
                $body = (string) $response->getBody();
        }
        return [
            'status_code' => $statusCode,
            'headers'     => $headers,
            'body'        => $body,
            'response'    => $response,
        ];
    }
}
