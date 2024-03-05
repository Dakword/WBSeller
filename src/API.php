<?php

declare(strict_types=1);

namespace Dakword\WBSeller;

use Dakword\WBSeller\API\Endpoint\{
    Adv, Analytics, Content, Feedbacks, Marketplace, Prices, Promo,
    Questions, Recommendations, Statistics, Tariffs
};

class API
{
    private string $apiBaseUrl = 'https://suppliers-api.wildberries.ru';
    private string $statBaseUrl = 'https://statistics-api.wildberries.ru';
    private string $advBaseUrl = 'https://advert-api.wildberries.ru';
    private string $recomBaseUrl = 'https://recommend-api.wildberries.ru';
    private string $fbBaseUrl = 'https://feedbacks-api.wildberries.ru';
    private string $commonBaseUrl = 'https://common-api.wildberries.ru';
    private string $analyticsBaseUrl = 'https://seller-analytics-api.wildberries.ru';
    private array $apiKeys;
    private string $masterKey;
    private ?string $proxy = null;

    /**
     * @param array $options [
     *  'keys' => [
     *     'adv' => '',
     *     'analytics' => '',
     *     'content' => 'Content_key',
     *     'feedbacks' => 'FB_key',
     *     'marketplace' => 'Marketplace_key',
     *     'prices' => '',
     *     'promo' => '',
     *     'questions' => 'FB_key',
     *     'recommendations' => '',
     *     'statistics' => '',
     *   ],
     *   'masterkey' => 'alternative_universal_key'
     * ]
     */
    function __construct(array $options = [])
    {
        $this->apiKeys = $options['keys'] ?? [];
        $this->masterKey = $options['masterkey'] ?? '';
    }

    private function getKey($keyName): string
    {
        return isset($this->apiKeys[$keyName]) && $this->apiKeys[$keyName] ? $this->apiKeys[$keyName] : $this->masterKey;
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
    
    public function getProxy()
    {
        return $this->proxy;
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

    public function setCommonBaseUrl(string $baseUrl): void
    {
        $this->commonBaseUrl = rtrim($baseUrl, '/');
    }

    public function Adv(): Adv
    {
        return new Adv($this->advBaseUrl, $this->getKey('adv'), $this->proxy);
    }

    public function Analytics(): Analytics
    {
        return new Analytics($this->apiBaseUrl, $this->getKey('analytics'), $this->proxy);
    }

    public function Content(): Content
    {
        return new Content($this->apiBaseUrl, $this->getKey('content'), $this->proxy);
    }

    public function Feedbacks(): Feedbacks
    {
        return new Feedbacks($this->fbBaseUrl, $this->getKey('feedbacks'), $this->proxy);
    }

    public function Marketplace(): Marketplace
    {
        return new Marketplace($this->apiBaseUrl, $this->getKey('marketplace'), $this->proxy);
    }

    public function Prices(): Prices
    {
        return new Prices($this->apiBaseUrl, $this->getKey('prices'), $this->proxy);
    }

    public function Promo(): Promo
    {
        return new Promo($this->apiBaseUrl, $this->getKey('promo'), $this->proxy);
    }

    public function Questions(): Questions
    {
        return new Questions($this->fbBaseUrl, $this->getKey('questions'), $this->proxy);
    }

    public function Recommendations(): Recommendations
    {
        return new Recommendations($this->recomBaseUrl, $this->getKey('recommendations'), $this->proxy);
    }

    public function Statistics(): Statistics
    {
        return new Statistics($this->statBaseUrl, $this->getKey('statistics'), $this->proxy);
    }

    public function Tariffs(): Tariffs
    {
        return new Tariffs($this->commonBaseUrl, $this->getKey('statistics'), $this->proxy);
    }

}
