## [WBSeller API](/docs/API.md) / Calendar()

```php
$wbSellerAPI = new \Dakword\WBSeller\API($options);
$Calendar = $wbSellerAPI->Calendar();
```

Wildberries API / [**Календарь акций**](https://openapi.wb.ru/prices/api/ru/#tag/Kalendar-akcij)

| :speech_balloon: | :cloud: | [Calendar()](/src/API/Endpoint/Calendar.php) |
| ---------------- | ------- | -------------------------------------------- |
| Список акций                       | /api/v1/calendar/promotions               | Calendar()->**promotions()**             |
| Детальная информация __по акции__  | /api/v1/calendar/promotions/datails       | Calendar()->**promotionDetails()**       |
| Список товаров для участия в акции | /api/v1/calendar/promotions/nomenclatures | Calendar()->**promotionNomenclatures()** |
| Добавить товар в акцию             | /api/v1/calendar/promotions/upload        | Calendar()->**promotionUpload()**        |
