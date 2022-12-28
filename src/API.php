<?php

declare(strict_types=1);

namespace Dakword\WBSeller;

use Dakword\WBSeller\API\Endpoint\{
    Content,
    Marketplace,
    Prices,
    Promo,
    Statistics
};

class API
{
    const WB_API_VERSION = '1.7';

    private string $apiBaseUrl = 'https://suppliers-api.wildberries.ru';
    private string $statBaseUrl = 'https://statistics-api.wildberries.ru';
    private string $apiKey;
    private string $statKey;

    function __construct(array $options)
    {
        $this->apiKey = $options['apikey'];
        $this->statKey = $options['statkey'];
    }

    public function setApiBaseUrl(string $baseUrl): void
    {
        $this->apiBaseUrl = rtrim($baseUrl, '/');
    }

    public function setStatBaseUrl(string $baseUrl): void
    {
        $this->statBaseUrl = rtrim($baseUrl, '/');
    }

    public function Content(): Content
    {
        return new Content($this->apiBaseUrl, $this->apiKey);
    }

    public function Marketplace(): Marketplace
    {
        return new Marketplace($this->apiBaseUrl, $this->apiKey);
    }

    public function Prices(): Prices
    {
        return new Prices($this->apiBaseUrl, $this->apiKey);
    }

    public function Promo(): Promo
    {
        return new Promo($this->apiBaseUrl, $this->apiKey);
    }

    public function Statistics(): Statistics
    {
        return new Statistics($this->statBaseUrl, $this->statKey);
    }

}
