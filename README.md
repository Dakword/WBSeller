# WBSeller

Библиотека для работы с [Wildberries API](https://openapi.wb.ru)

⚡ <b>Теги. Корзина. Лимиты.</b>

### Работа с API
```php

$wbSellerAPI = new \Dakword\WBSeller\API([
    'apikey' => 'XXX',
    'statkey' => 'YYY',
    'advkey' => 'ZZZ',
]);

// Proxy
$wbSellerAPI->useProxy('http://122.123.123.123:8088');

// API контента
$contentApi = $wbSellerAPI->Content();
// API цен
$pricesApi = $wbSellerAPI->Prices();
// API marketplace
$marketApi = $wbSellerAPI->Marketplace();
// API скидок и промокодов
$promoApi = $wbSellerAPI->Promo();
// API статистики
$statApi = $wbSellerAPI->Statistics();
// API рекламы
$advApi = $wbSellerAPI->Adv();
// API вопросов
$questionsApi = $wbSellerAPI->Questions();
// API отзывов
$fbApi = $wbSellerAPI->Feedbacks();

// Получить список НМ
$result = $contentApi->getCardsList();
if (!$result->error) {
    var_dump($result->data->cards, $result->data->cursor);
}

// Информация по ценам для товаров с ненулевым остатком
$info = $pricesApi->getPricesOnStock();
var_dump($info);

// Cписок складов поставщика
$warehouses = $wbSellerAPI->Marketplace()->getWarehouses();
var_dump($warehouses);

// Заказы, сделанные сегодня (💡 С автоповтором запросов)
$orders = $statApi->retryOnTooManyRequests(10, 1000)->ordersOnDate(new \DateTime(date('Y-m-d')));
var_dump($orders);

// Создание КТ
try {
    $createCardResult = $contentApi->createCard([
        'vendorCode' => 'A0001',
        'characteristics' => [
            (object) ['Предмет' => 'Платья'],
            (object) ['Цвет' => 'Зеленый'],
        ],
        'sizes' => [
            (object) [
                'techSize' => (string) 39,
                'wbSize' => '',
                'price' => (int) 3999.99,
                'skus' => [ (string) 1000000001 ]
            ]
        ],
    ]);
    if ($createCardResult->error) {
        echo 'Ошибка создания карточки: ' . $createCardResult->errorText;
    } else {
        echo 'Запрос на создание карточки отправлен в очередь';
    }
} catch (\Exception $exc) {
    echo 'Исключение при создании карточки: ' . $exc->getMessage();
}

и т.д. и т.п. (смотрим тесты)
```
