<?php

declare(strict_types=1);

namespace Dakword\WBSeller\API;

use Dakword\WBSeller\API\Client;
use Dakword\WBSeller\Exception\ApiClientException;

abstract class AbstractEndpoint
{
    protected string $baseUrl;
    protected string $apiKey;
    private Client $Client;

    public function __construct(string $baseUrl, string $key)
    {
        $this->baseUrl = rtrim($baseUrl, '/');
        $this->apiKey = $key;
        $this->Client = new Client($baseUrl, $key);

        if (method_exists($this, 'middleware')) {
            $this->Client->addMiddleware($this->middleware());
        }
    }

    protected function request(string $path, string $method, array $data = [], array $addonHeaders = [])
    {
        $result = $this->Client->request($path, $method, $data, $addonHeaders);

        // >>> Exception "401 Unauthorized"
        $errorSensors = [
            '(api-new) can\'t decode supplier key',
            '(api-new) some chars in key are wrong',
            '(api-new) supplier key not found',
            'proxy: invalid token',
            'proxy: unauthorized',
            'request rejected, unathorized'
        ];
        $inErrors = function (string $message) use($errorSensors): bool {
            foreach ($errorSensors as $error) {
                if (strpos($message, $error) !== false) {
                    return true;
                }
            }
            return false;
        };
        if (is_string($result) && $inErrors($result)) {
            throw new ApiClientException($result, 401);
        }
        if (is_object($result) && property_exists($result, 'errors') && count($result->errors) && $inErrors($result->errors[0])) {
            throw new ApiClientException($result->errors[0], 401);
        }

        return $result;
    }

    public function responseCode()
    {
        return $this->Client->responseCode;
    }

    public function responsePhrase()
    {
        return $this->Client->responsePhrase;
    }

    public function responseHeaders()
    {
        return $this->Client->responseHeaders;
    }

    public function rawResponse()
    {
        return $this->Client->rawResponse;
    }

    public function response()
    {
        return $this->Client->response;
    }

    public function responseRate()
    {
        return [
            'limit' => $this->Client->rateLimit,
            'remaining' => $this->Client->rateRemaining,
            'reset' => $this->Client->rateReset,
        ];
    }

}
