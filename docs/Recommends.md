## [WBSeller API](/docs/API.md) / Recommends()

```php
$wbSellerAPI = new \Dakword\WBSeller\API($options);
$Recommens = $wbSellerAPI->Recommends();
```

Wildberries API / [**Рекомендации**](https://openapi.wb.ru/recommendations/api/ru/)

| :speech_balloon: | :cloud: | [Recommends()](/src/API/Endpoint/Recommendations.php) |
| ---------------- | ------- | ----------------------------------------------------- |
| Список рекомендаций       | /api/v1/list | Recommends()->**list()**   |
| Добавление рекомендаций   | /api/v1/ins  | Recommends()->**add()**    |
| Управление рекомендациями | /api/v1/set  | Recommends()->**update()** |
| Удаление рекомендаций     | /api/v1/del  | Recommends()->**delete()** |
