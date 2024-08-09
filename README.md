# WBSeller
Библиотека для работы с [Wildberries API](https://openapi.wb.ru)

```php
$API = new \Dakword\WBSeller\API($options = [
    'masterkey' => 'token'
]);
// API Контента
$contentApi = $API->Content();
```
| API                   | Endpoint                      | $options<br>['keys' / 'apiurls']<br>['?'] | 'apiurls' defaults |
| --------------------- | ----------------------------- | --------------------- | ------------------------------ |
| Контент               | $API->**Content()**           | content           | https://suppliers-api.wildberries.ru
| Цены и скидки         | $API->**Prices()**            | prices            | https://discounts-prices-api.wildberries.ru
| Маркетплейс           | $API->**Marketplace()**       | marketplace       | https://marketplace-api.wildberries.ru
| Статистика            | $API->**Statistic()**         | statistics        | https://statistics-api.wildberries.ru
| Аналитика             | $API->**Analitics()**         | analytics         | https://seller-analytics-api.wildberries.ru
| Продвижение           | $API->**Adv()**               | adv               | https://advert-api.wildberries.ru
| Рекомендации          | $API->**Recommendations()**   | recommendations   | https://recommend-api.wildberries.ru
| Вопросы               | $API->**Questions()**         | feedbacks         | https://feedbacks-api.wildberries.ru
| Отзывы                | $API->**Feedbacks()**         | feedbacks         | https://feedbacks-api.wildberries.ru
| Тарифы                | $API->**Tariffs()**           | tariffs           | https://common-api.wildberries.ru
| Чат с покупателями    | $API->**Chat()**              | chat              | https://buyer-chat-api.wildberries.ru
| Возвраты покупателями | $API->**Returns()**           | returns           | https://returns-api.wildberries.ru
| Документы             | $API->**Documents()**         | documents         | https://documents-api.wildberries.ru

### Примеры работы с API
```php
$wbSellerAPI = new \Dakword\WBSeller\API([
    'keys' => [
        'content' => 'Content_key',
        'feedbacks' => 'FB_key',
        'marketplace' => 'Marketplace_key',
        'questions' => 'FB_key',
    ],
    'masterkey' => 'multi_key', // 'content' + 'prices' + ...
    'apiurls' => [
        'content'         => 'https://suppliers-api.wb.ru',
        'feedbacks'       => 'https://feedbacks-api.wildberries.ru',
        'adv'             => 'https://advert-api-sandbox.wildberries.ru',
        'analytics'       => 'https://abc.site.ru',
    ],
    'locale' => 'ru'
]);

// Proxy
$wbSellerAPI->useProxy('http://122.123.123.123:8088');
// Locale
$wbSellerAPI->setLocale('en');

$contentApi = $wbSellerAPI->Content();
$pricesApi = $wbSellerAPI->Prices();
$marketApi = $wbSellerAPI->Marketplace();

// subAPI контента - теги
$tagsApi = $wbSellerAPI->Content()->Tags();

// Получить список НМ
$result = $contentApi->getCardsList();
if (!$result->error) {
    var_dump($result->cards, $result->cursor);
}

// Получение информации по ценам и скидкам
$info = $pricesApi->getPrices();
var_dump($info);

// Cписок складов поставщика
$warehouses = $wbSellerAPI->Marketplace()->Warehouses()->list();
var_dump($warehouses);

// Заказы FBS (С автоповтором запросов 💡)
$orders = $marketApi->retryOnTooManyRequests(10, 1000)->getOrders();
var_dump($orders);

// Создание КТ
try {
    $createCardResult = $contentApi->createCard([
        'subjectID' => 105,
		'variants' => [
            [
                'vendorCode' => 'A0001',
                'title' => 'Наименование',
                'description' => 'Описание',
                'brand' => 'Бренд',
                'dimensions' => [
                    'length' => 55,
                    'width' => 40,
                    'height' => 15,
                ],
                'characteristics' => [
                    [
                        'id' => 12,
                        'value' => 'свободный крой',
                    ],
                    [
                        'id' => 88952,
                        'value' => 200,
                    ],
                    [
                        'id' => 14177449,
                        'value' => ['red'],
                    ],
                ],
                'sizes' => [
                    [
                        'techSize' => '39',
                        'wbSize' => '',
                        'price' => (int) 3999.99,
                        'skus' => [ '1000000001' ]
                    ]
                ],
            ],
        ]
    ]);
    if ($createCardResult->error) {
        echo 'Ошибка создания карточки: ' . $createCardResult->errorText;
    } else {
        echo 'Запрос на создание карточки отправлен';
    }
} catch (\Dakword\WBSeller\Exception\WBSellerException $exc) {
    echo 'Исключение при создании карточки: ' . $exc->getMessage();
}
```