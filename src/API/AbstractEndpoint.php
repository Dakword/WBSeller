<?php

declare(strict_types=1);

namespace Dakword\WBSeller\API;

use Dakword\WBSeller\API\Client;
use Dakword\WBSeller\Exception\ApiClientException;
use Dakword\WBSeller\Exception\ApiTimeRestrictionsException;

abstract class AbstractEndpoint
{
    private int $attempts = 1;
    private int $retryDelay = 0;
    private Client $Client;

    public function __construct(string $baseUrl, string $key)
    {
        $this->Client = new Client(rtrim($baseUrl, '/'), $key);

        if (method_exists($this, 'middleware')) {
            $this->Client->addMiddleware($this->middleware());
        }
    }

    protected function getRequest(string $path, array $data = [], array $addonHeaders = [])
    {
        return $this->request($path, $data, 'GET', $addonHeaders);
    }

    protected function postRequest(string $path, array $data = [], array $addonHeaders = [])
    {
        return $this->request($path, $data, 'POST', $addonHeaders);
    }

    protected function putRequest(string $path, array $data = [], array $addonHeaders = [])
    {
        return $this->request($path, $data, 'PUT', $addonHeaders);
    }

    protected function patchRequest(string $path, array $data = [], array $addonHeaders = [])
    {
        return $this->request($path, $data, 'PATCH', $addonHeaders);
    }

    protected function deleteRequest(string $path, array $data = [], array $addonHeaders = [])
    {
        return $this->request($path, $data, 'DELETE', $addonHeaders);
    }

    protected function multipartRequest(string $path, array $data = [], array $addonHeaders = [])
    {
        return $this->request($path, $data, 'MULTIPART', $addonHeaders);
    }

    /**
     * Автоматически повторять запросы в случае ответа сервера "429 Too Many Requests"
     * 
     * @param int $attempts Количество попыток выполнения запроса
     * @param int $delay    Задержка в миллисекундах между попытками
     * @return self
     */
    public function retryOnTooManyRequests(int $attempts = 5, int $delay = 5_000): self
    {
        $this->attempts = $attempts;
        $this->retryDelay = $delay;

        return $this;
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

    private function request(string $path, array $data = [], string $method = 'GET', array $addonHeaders = [])
    {
        $attempt = 1;

        beginRequest:
        $result = $this->Client->request($path, $method, $data, $addonHeaders);

        if (
            $this->responseCode() == 400 && property_exists($result, 'error') && $result->error 
            && mb_strpos(mb_strtolower($result->errorText), 'временные ограничения') !== false
        ) {
            throw new ApiTimeRestrictionsException($result->errorText);
            
        } elseif ($this->responseCode() == 401) {
            /*
             * "401 Unauthorized"
             * 
             * (api-new) can\'t decode supplier key
             * (api-new) some chars in key are wrong
             * (api-new) supplier key not found
             * proxy: invalid token
             * proxy: unauthorized
             * request rejected, unathorized
             */
            if (is_string($result)) {
                throw new ApiClientException($result, 401);
            } elseif (is_object($result) && property_exists($result, 'errors') && count($result->errors)) {
                throw new ApiClientException($result->errors[0], 401);
            } else {
                throw new ApiClientException('Unauthorized', 401);
            }
        } elseif ($this->responseCode() == 429) {
            /* 
             * "429 Too Many Requests"
             * 
             * { errors: ["Технический перерыв до 16:00"] }
             * { errors: ["(api-new) too many requests"] }
             */
            if(mb_strpos(mb_strtolower($result->errors[0]), 'технический перерыв') !== false) {
                throw new ApiTimeRestrictionsException($result->errors[0]);
            }
            if ($attempt >= $this->attempts) {
                throw new ApiClientException($result->errors[0], 429);
            }
            usleep($this->retryDelay * 1_000);
            $attempt++;

            goto beginRequest;
        }
        return $result;
    }

}
