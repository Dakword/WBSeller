<?php

declare(strict_types=1);

namespace Dakword\WBSeller;

use Dakword\WBSeller\API\Endpoint\{
    Adv, Analytics, Chat, Content, Documents, Feedbacks, Marketplace, Prices,
    Questions, Recommendations, Returns, Statistics, Tariffs
};

class API
{
    private array $apiUrls = [
        'adv'         => 'https://advert-api.wildberries.ru',
        'analytics'   => 'https://seller-analytics-api.wildberries.ru',
        'chat'        => 'https://buyer-chat-api.wildberries.ru',
        'content'     => 'https://suppliers-api.wildberries.ru',
        'documents'   => 'https://documents-api.wildberries.ru',
        'feedbacks'   => 'https://feedbacks-api.wildberries.ru',
        'marketplace' => 'https://marketplace-api.wildberries.ru',
        'prices'      => 'https://discounts-prices-api.wildberries.ru',
        'questions'   => 'https://feedbacks-api.wildberries.ru',
        'recommends'  => 'https://recommend-api.wildberries.ru',
        'returns'     => 'https://returns-api.wildberries.ru',
        'statistics'  => 'https://statistics-api.wildberries.ru',
        'tariffs'     => 'https://common-api.wildberries.ru',
    ];
    private array $apiKeys;
    private string $masterKey;
    private string $locale;
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
     *     'questions' => 'FB_key',
     *     'recommends' => '',
     *     'statistics' => '',
     *     'tariffs' => '',
     *   ],
     *   'masterkey' => 'alternative_universal_key',
     *   'apiurls' => [
     *     'adv' => 'url',
     *     'analytics' => '',
     *     'content' => 'url',
     *     'feedbacks' => 'url',
     *     'marketplace' => '',
     *     'prices' => '',
     *     'questions' => '',
     *     'recommends' => '',
     *     'statistics' => '',
     *     'tariffs' => '',
     *   ],
     *   'locale' => 'ru'
     * ]
     */
    function __construct(array $options = [])
    {
        $this->apiKeys = $options['keys'] ?? [];
        $this->masterKey = $options['masterkey'] ?? '';

        $locale = $options['locale'] ?? null;
        $this->setLocale(!is_null($locale) ? $locale : (getenv('WBSELLER_LOCALE') ?: 'ru'));

        if(isset($options['apiurls']) && is_array($options['apiurls'])) {
            foreach($options['apiurls'] as $apiName => $apiUrl) {
                $arrayKey = strtolower($apiName);
                // "recommendations" => "recommends"
                if($arrayKey == 'recommendations') {
                    $arrayKey = 'recommends';
                }
                if(array_key_exists($arrayKey, $this->apiUrls)) {
                    $this->apiUrls[$arrayKey] = rtrim($apiUrl, '/');
                }
            }
        }
    }

    private function getKey($keyName): string
    {
        // ! "recommends" <= "recommendations"
        if($keyName == 'recommends' && !isset($this->apiKeys['recommends']) && isset($this->apiKeys['recommendations'])) {
            $keyName = 'recommendations';
        }
        return isset($this->apiKeys[$keyName]) && is_string($this->apiKeys[$keyName]) && $this->apiKeys[$keyName] !== ''
            ? $this->apiKeys[$keyName]
            : $this->masterKey;
    }

    /**
     * Использовать для запросов прокси
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

    public function setLocale(string $locale): void
    {
        $this->locale = $locale;
    }

    public function getLocale(): string
    {
        return $this->locale;
    }

    public function setApiUrl(string $apiName, string $apiUrl): void
    {
        $arrayKey = strtolower($apiName);
        if(array_key_exists($arrayKey, $this->apiUrls)) {
            $this->apiUrls[$arrayKey] = rtrim($apiUrl, '/');
        }
    }

    public function Adv(): Adv
    {
        return new Adv($this->apiUrls['adv'], $this->getKey('adv'), $this->proxy, $this->locale);
    }

    public function Analytics(): Analytics
    {
        return new Analytics($this->apiUrls['analytics'], $this->getKey('analytics'), $this->proxy, $this->locale);
    }

    public function Chat(): Chat
    {
        return new Chat($this->apiUrls['chat'], $this->getKey('chat'), $this->proxy, $this->locale);
    }

    public function Content(): Content
    {
        return new Content($this->apiUrls['content'], $this->getKey('content'), $this->proxy, $this->locale);
    }

    public function Documents(): Documents
    {
        return new Documents($this->apiUrls['documents'], $this->getKey('documents'), $this->proxy, $this->locale);
    }

    public function Feedbacks(): Feedbacks
    {
        return new Feedbacks($this->apiUrls['feedbacks'], $this->getKey('feedbacks'), $this->proxy, $this->locale);
    }

    public function Marketplace(): Marketplace
    {
        return new Marketplace($this->apiUrls['marketplace'], $this->getKey('marketplace'), $this->proxy, $this->locale);
    }

    public function Prices(): Prices
    {
        return new Prices($this->apiUrls['prices'], $this->getKey('prices'), $this->proxy, $this->locale);
    }

    public function Questions(): Questions
    {
        return new Questions($this->apiUrls['questions'], $this->getKey('questions'), $this->proxy, $this->locale);
    }

    public function Recommends(): Recommendations
    {
        return new Recommends($this->apiUrls['recommends'], $this->getKey('recommends'), $this->proxy, $this->locale);
    }

    public function Recommendations(): Recommendations
    {
        return $this->Recommends();
    }

    public function Returns(): Returns
    {
        return new Returns($this->apiUrls['returns'], $this->getKey('returns'), $this->proxy, $this->locale);
    }

    public function Statistics(): Statistics
    {
        return new Statistics($this->apiUrls['statistics'], $this->getKey('statistics'), $this->proxy, $this->locale);
    }

    public function Tariffs(): Tariffs
    {
        return new Tariffs($this->apiUrls['tariffs'], $this->getKey('tariffs'), $this->proxy, $this->locale);
    }

}
