# WBSeller

Библиотека для работы с [Wildberries API 1.4](https://openapi.wb.ru)

### Работа с API
```php

$wbSellerAPI = new \Dakword\WBSeller\API([
    'apikey' => 'XXX',
    'statkey' => 'YYY',
]);

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

    if ($createCardResult->error) {
        echo 'Ошибка создания карточки: ' . $createCardResult->errorText;
    } else {
        echo 'Запрос на создание карточки отправлен в очередь';
    }
	
} catch (\Exception $exc) {
    echo 'Исключение при создании карточки: ' . $exc->getMessage();
}

```

### Запросы
```php

$wbSellerAPI = new \Dakword\WBSeller\API([
    'apikey' => 'XXX',
    'statkey' => 'YYY',
]);
$Query = new \Dakword\WBSeller\Query($wbSellerAPI);

// Список НМ
$cardsList = $Query->CardsList()->find('20')->withPhoto()->sortDesc()->getAll();
var_dump($cardsList);

$cardsListQuery = $Query->CardsList()->find('iPhone')->perPage(250);
$firstPage = $cardsListQuery->getFirst();
var_dump($firstPage);
if ($cardsListQuery->hasNext()) {
    $nextPage = $cardsListQuery->getNext($cursor);
    var_dump($nextPage);
	if ($cardsListQuery->hasNext()) {
		$cursor = $cardsListQuery->getCursor();
		// ...
		$renewCardsListQuery = $Query->CardsList()->find('iPhone')->perPage(250);
		$pageByCursor = $renewCardsListQuery->getNext($cursor);
		var_dump($pageByCursor);
	}
}

// Список ошибочных НМ
$errorCards = $Query->ErrorCardsList()->getAll();
var_dump($errorCards);

$vendorCode = 'ABCD';
$result = $Query->ErrorCardsList()->find($vendorCode);
if (!is_null($result)) {
    var_dump($result->errors);
}

$vendorCodes = ['ABCD', 'XYZ'];
$results = $Query->ErrorCardsList()->find($vendorCodes);
if (array_key_exists('ABCD', $results)) {
    var_dump($results['ABCD']->errors);
}

```
