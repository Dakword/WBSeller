<?php

declare(strict_types=1);

namespace Dakword\WBSeller;

use Dakword\WBSeller\API\Endpoint\{
    Adv, Content, Feedbacks, Marketplace, Prices, Promo,
    Questions, Recommendations, Statistics
};

class API
{
    public const WB_API_VERSION = '2.10';

    private string $apiBaseUrl = 'https://suppliers-api.wildberries.ru';
    private string $statBaseUrl = 'https://statistics-api.wildberries.ru';
    private string $advBaseUrl = 'https://advert-api.wildberries.ru';
    private string $recomBaseUrl = 'https://recommend-api.wildberries.ru';
    private string $fbBaseUrl = 'https://feedbacks-api.wildberries.ru';
    private string $apiKey;
    private string $statKey;
    private string $advKey;
    private ?string $proxy = null;

    /**
     * @param array $options [
     *     'apikey' => 'XXX',
     *     'statkey' => 'YYY',
     *     'advkey' => 'ZZZ',
     * ]
     */
    function __construct(array $options)
    {
        $this->apiKey = $options['apikey'] ?? '';
        $this->statKey = $options['statkey'] ?? '';
        $this->advKey = $options['advkey'] ?? '';
    }

    /**
     * Использовать для запросов HTTP-прокси
     * 
     * @param string $proxyUrl http://username:password@192.168.16.1:10
     */
    public function useProxy(string $proxyUrl)
    {
        $this->proxy = $proxyUrl;
    }
    
    public function setApiBaseUrl(string $baseUrl): void
    {
        $this->apiBaseUrl = rtrim($baseUrl, '/');
    }

    public function setStatBaseUrl(string $baseUrl): void
    {
        $this->statBaseUrl = rtrim($baseUrl, '/');
    }

    public function setAdvBaseUrl(string $baseUrl): void
    {
        $this->advBaseUrl = rtrim($baseUrl, '/');
    }

    public function setRecomBaseUrl(string $baseUrl): void
    {
        $this->recomBaseUrl = rtrim($baseUrl, '/');
    }

    public function setFeedBacksBaseUrl(string $baseUrl): void
    {
        $this->fbBaseUrl = rtrim($baseUrl, '/');
    }

    public function Adv(): Adv
    {
        return new Adv($this->advBaseUrl, $this->advKey, $this->proxy);
    }

    public function Content(): Content
    {
        return new Content($this->apiBaseUrl, $this->apiKey, $this->proxy);
    }

    public function Feedbacks(): Feedbacks
    {
        return new Feedbacks($this->fbBaseUrl, $this->apiKey, $this->proxy);
    }

    public function Marketplace(): Marketplace
    {
        return new Marketplace($this->apiBaseUrl, $this->apiKey, $this->proxy);
    }

    public function Prices(): Prices
    {
        return new Prices($this->apiBaseUrl, $this->apiKey, $this->proxy);
    }

    public function Promo(): Promo
    {
        return new Promo($this->apiBaseUrl, $this->apiKey, $this->proxy);
    }

    public function Questions(): Questions
    {
        return new Questions($this->fbBaseUrl, $this->apiKey, $this->proxy);
    }

    public function Recommendations(): Recommendations
    {
        return new Recommendations($this->recomBaseUrl, $this->apiKey, $this->proxy);
    }

    public function Statistics(): Statistics
    {
        return new Statistics($this->statBaseUrl, $this->statKey, $this->proxy);
    }

}
