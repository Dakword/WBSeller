## [WBSeller API](/docs/API.md) / Prices()

```php
$wbSellerAPI = new \Dakword\WBSeller\API($options);
$Prices = $wbSellerAPI->Prices();
```

Wildberries API / [**Цены и скидки**](https://openapi.wb.ru/prices/api/ru/)

| :speech_balloon: | :cloud: | [Prices()](/src/API/Endpoint/Prices.php) |
| ---------------- | ------- | ---------------------------------------- |
| [**Установка цен и скидок**](https://openapi.wb.ru/prices/api/ru/#tag/Ustanovka-cen-i-skidok) |||
| Установить цены и скидки                   | /api/v2/upload/task        | Statistics()->**upload()**                |
| Установить цены для размеров               | /api/v2/upload/task/size   | Statistics()->**uploadSizes()**           |
| [**Состояние загрузок**](https://openapi.wb.ru/prices/api/ru/#tag/Sostoyaniya-zagruzok) |||
| Состояние обработанной загрузки            | /api/v2/history/tasks      | Statistics()->**getUploadStatus()**       |
| Состояние необработанной загрузки          | /api/v2/buffer/tasks       | Statistics()->**getBufferUploadStatus()** |
| Детализация обработанной загрузки          | /api/v2/history/goods/task | Statistics()->**getUpload()**             |
| Детализация необработанной загрузки        | /api/v2/buffer/goods/task  | Statistics()->**getBufferUpload()**       |
| [**Списки товаров**](https://openapi.wb.ru/prices/api/ru/#tag/Spiski-tovarov) |||
| Цены и скидки                              | /api/v2/list/goods/filter  | Statistics()->**getPrices()**             |
| Цены и скидки для артикула WB              | /api/v2/list/goods/filter  | Statistics()->**getNmIdPrice()**          |
| Цены и скидки для для размеров артикула WB | /api/v2/list/goods/size/nm | Statistics()->**getNmIdSizesPrices()**    |
| [**Карантин**](https://openapi.wb.ru/prices/api/ru/#tag/Karantin) |||
| Получить товары в карантине                | /api/v2/quarantine/goods   | Statistics()->**quarantine()**            |
