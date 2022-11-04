# WBSeller

Библиотека для работы с [Wildberries API 1.4](https://openapi.wb.ru)


```php

$wbSeller = new \Dakword\WBSeller\API([
    'apikey' => 'XXX',
    'statkey' => 'YYY',
]);

// API контента
$contentApi = $wbSeller->Content();

// API цен
$pricesApi = $wbSeller->Prices();

// API marketplace
$marketApi = $wbSeller->Marketplace();

// API скидок и промокодов
$promoApi = $wbSeller->Promo();

// API статистики
$statApi = $wbSeller->Statistics();



// Получить список НМ
$result = $contentApi->getCardsList();
if(!$result->error) {
    var_dump($result->data->cards, $result->data->cursor);
}

// Информация по ценам для товаров с ненулевым остатком
$info = $pricesApi->getPricesOnStock();
var_dump($info);

// Cписок складов поставщика
$warehouses = $wbSeller->Marketplace()->getWarehouses();
var_dump($warehouses);

// Заказы, сделанные сегодня
$orders = $statApi->ordersOnDate(new \DateTime(date('Y-m-d')));
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

    if($createCardResult->error) {
        echo 'Ошибка создания карточки: ' . $createCardResult->errorText;
    } else {
        echo 'Запрос на создание карточки отправлен в очередь';
    }
	
} catch (\Exception $exc) {
    echo 'Исключение при создании карточки: ' . $exc->getMessage();
}

```