# WBSeller

Библиотека для работы с [Wildberries API](https://openapi.wb.ru)<br>

💡 Автоматические кампании `Adv::Auto()`<br>
💡 Кампании в поиске и поиск+каталог `Adv::SearchCatalog()`<br>
💡 Медиакампании `Adv::Media()`<br>

### Работа с API
```php
$wbSellerAPI = new \Dakword\WBSeller\API([
    // 'adv', 'analytics', 'chat', 'content', 'documents', 'feedbacks', 'marketplace',
    // 'prices', 'questions', 'recommendations', 'statistics'
    'keys' => [
        'content' => 'Content_key',
        'feedbacks' => 'FB_key',
        'marketplace' => 'Marketplace_key',
        'questions' => 'FB_key',
    ],
    'masterkey' => 'multi_key', // 'content' + 'prices'
    // 'adv', 'analytics', 'chat', 'content', 'documents', 'feedbacks', 'marketplace', 'prices', 'questions',
    // 'recommendations', 'returns', 'statistics', 'tariffs'
    'apiurls' => [
        'adv'             => 'https://advert-api-sandbox.wildberries.ru',
        'analytics'       => 'https://abc.site.ru',
        'content'         => 'https://suppliers-api.wb.ru',
        'feedbacks'       => 'https://feedbacks-api.wildberries.ru',
    ],
]);

// Proxy
$wbSellerAPI->useProxy('http://122.123.123.123:8088');

// API контента
$contentApi = $wbSellerAPI->Content();
// API цен
$pricesApi = $wbSellerAPI->Prices();
// API marketplace
$marketApi = $wbSellerAPI->Marketplace();
// API статистики
$statApi = $wbSellerAPI->Statistics();
// API рекламы
$advApi = $wbSellerAPI->Adv();
// API вопросов
$questionsApi = $wbSellerAPI->Questions();
// API отзывов
$fbApi = $wbSellerAPI->Feedbacks();
// subAPI контента - теги
$tagsApi = $wbSellerAPI->Content()->Tags();

// Получить список НМ
$result = $contentApi->getCardsList();
if (!$result->error) {
    var_dump($result->cards, $result->cursor);
}

// Цены товаров с ненулевым остатком
$info = $pricesApi->getPricesOnStock();
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