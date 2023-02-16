<?php

declare(strict_types=1);

namespace Dakword\WBSeller;

use Dakword\WBSeller\API\Endpoint\{
    Adv,
    Content,
    Marketplace,
    Prices,
    Promo,
    Recommendations,
    Statistics
};

class API
{
    public const WB_API_VERSION = '1.7';

    private string $apiBaseUrl = 'https://suppliers-api.wildberries.ru';
    private string $statBaseUrl = 'https://statistics-api.wildberries.ru';
    private string $advBaseUrl = 'https://advert-api.wb.ru';
    private string $recomBaseUrl = 'https://recommend-api.wb.ru';
    private string $apiKey;
    private string $statKey;
    private string $advKey;
    private string $recomKey;

    /**
     * @param array $options [
     *     'apikey' => 'XXX',
     *     'statkey' => 'YYY',
     *     'advkey' => 'ZZZ',
     *     'recomkey' => 'XYZ',
     * ]
     */
    function __construct(array $options)
    {
        $this->apiKey = $options['apikey'] ?? '';
        $this->statKey = $options['statkey'] ?? '';
        $this->advKey = $options['advkey'] ?? '';
        $this->recomKey = $options['recomkey'] ?? '';
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

    public function Adv(): Adv
    {
        return new Adv($this->advBaseUrl, $this->advKey);
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

    public function Recommendations(): Recommendations
    {
        return new Recommendations($this->recomBaseUrl, $this->apiKey);
    }

    public function Statistics(): Statistics
    {
        return new Statistics($this->statBaseUrl, $this->statKey);
    }

}
