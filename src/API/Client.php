<?php

declare(strict_types=1);

namespace Dakword\WBSeller\API;

use GuzzleHttp\Client as HttpClient;
use GuzzleHttp\Exception\ClientException;
use GuzzleHttp\Exception\RequestException;
use GuzzleHttp\Handler\CurlHandler;
use GuzzleHttp\HandlerStack;
use InvalidArgumentException;

class Client
{
    public int $responseCode = 0;
    public ?string $responsePhrase = null;
    public array $responseHeaders = [];
    public ?string $rawResponse = null;
    public $response = null;
    public int $rateLimit = 0;
    public int $rateRemaining = 0;
    public int $rateReset = 0;
    private string $baseUrl;
    private string $apiKey;
    private HttpClient $Client;
    private HandlerStack $stack;

    function __construct(string $baseUrl, string $apiKey, ?string $proxyUrl)
    {
        $this->baseUrl = rtrim($baseUrl, '/');
        $this->apiKey = $apiKey;

        $this->stack = new HandlerStack();
        $this->stack->setHandler(new CurlHandler());

        $this->Client = new HttpClient([
            'timeout' => 0, // in seconds
            'verify' => false,
            'handler' => $this->stack,
            'proxy' => $proxyUrl,
        ]);
    }

    public function addMiddleware(callable $middleware, string $name = ''): void
    {
        $this->stack->push($middleware, $name);
    }

    /**
     * @throws RequestException
     * @throws InvalidArgumentException
     */
    public function request(string $method, string $path, array $params = [], array $addonHeaders = [])
    {
        $this->responseCode = 0;
        $this->responsePhrase = null;
        $this->responseHeaders = [];
        $this->rawResponse = null;
        $this->response = null;

        $defaultHeaders = [
            'Accept' => 'application/json',
            'Content-Type' => 'application/json',
            'Authorization' => $this->apiKey,
        ];
        $headers = array_merge($defaultHeaders, $addonHeaders);
        $url = $this->baseUrl . $path;
        
        try {
            switch (strtoupper($method)) {
                case 'GET':
                    $response = $this->Client->get($url, [
                        'headers' => $headers,
                        'query' => $params,
                    ]);
                    break;

                case 'POST':
                    $response = $this->Client->post($url, [
                        'headers' => $headers,
                        'body' => json_encode($params)
                    ]);
                    break;

                case 'PUT':
                    $response = $this->Client->put($url, [
                        'headers' => $headers,
                        'body' => json_encode($params)
                    ]);
                    break;

                case 'PATCH':
                    $response = $this->Client->patch($url, [
                        'headers' => $headers,
                        'body' => json_encode($params)
                    ]);
                    break;

                case 'DELETE':
                    $response = $this->Client->delete($url, [
                        'headers' => $headers,
                        'body' => json_encode($params)
                    ]);
                    break;

                case 'MULTIPART':
                    $response = $this->Client->post($url, [
                        'headers' => array_merge([
                            'Authorization' => $this->apiKey,
                            ], $addonHeaders),
                        'multipart' => $params,
                    ]);
                    break;

                default:
                    throw new InvalidArgumentException('Unsupported request method: ' . strtoupper($method));
            }
        } catch (RequestException | ClientException $exc) {
            if ($exc->hasResponse()) {
                $jsonDecoded = json_decode($exc->getResponse()->getBody()->getContents());
                if (!json_last_error()) {
                    /*
                      400 Bad Request
                      403 Forbidden
                      404 Not Found
                      409 Conflict
                      500 Internal Server Error
                          {["code"] => "InternalServerError", ["message"] => "Внутренняя ошибка сервера"}
                      ...
                     */
                    return $jsonDecoded;
                }
            }
            /*
              401 Unauthorized
              404 Not Found
              429 Too Many Requests
              0	cURL error 6: Could not resolve host
              0	cURL error 28: Operation timed out after * milliseconds with 0 out of 0 bytes received
              0	cURL error 56: OpenSSL SSL_read: Connection was reset, errno 10054
              0	cURL error 60: SSL certificate problem: self signed certificate in certificate chain
              ...
             */
            throw $exc;
        }

        $this->responseCode = $response->getStatusCode();
        $this->responsePhrase = $response->getReasonPhrase();
        $this->responseHeaders = $response->getHeaders();

        $this->rateLimit = (int)$response->getHeaderLine('X-RateLimit-Limit') ?: 0;
        $this->rateRemaining = (int)$response->getHeaderLine('X-RateLimit-Remaining') ?: 0;
        $this->rateReset = (int)$response->getHeaderLine('X-RateLimit-Reset') ?: 0;

        $responseContent = $response->getBody()->getContents();
        $this->rawResponse = $responseContent;

        $jsonDecoded = json_decode($responseContent);

        $this->response = (json_last_error() ? $responseContent : $jsonDecoded);

        return $this->response;
    }

}
