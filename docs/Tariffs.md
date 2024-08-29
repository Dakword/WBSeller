## [WBSeller API](/docs/API.md) / Tariffs()

```php
$wbSellerAPI = new \Dakword\WBSeller\API($options);
$Tariffs = $wbSellerAPI->Tariffs();
```

Wildberries API / [**Тарифы**](https://openapi.wb.ru/tariffs/api/ru/)

| :speech_balloon: | :cloud: | [Tariffs()](/src/API/Endpoint/Tariffs.php) |
| ---------------- | ------- | ----------------------------------------- |
| Проверка подключения к API     | /ping                       | Tariffs()->**ping()**       |
| [**Комиссии**](https://openapi.wb.ru/tariffs/api/ru/#tag/Komissii) |||
| Комиссия по категориям товаров | /api/v1/tariffs/commissioin | Tariffs()->**commission()** |
| [**Коэффициенты складов**](https://openapi.wb.ru/tariffs/api/ru/#tag/Koefficienty-skladov) |||
| Тарифы для коробов             | /api/v1/tariffs/box         | Tariffs()->**box()**        |
| Тарифы для монопаллет          | /api/v1/tariffs/pallet      | Tariffs()->**pallet()**     |
| [**Стоимость возврата продавцу**](https://openapi.wb.ru/tariffs/api/ru/#tag/Stoimost-vozvrata-prodavcu) |||
| Тарифы на возврат              | /api/v1/tariffs/return      | Tariffs()->**return()**     |
