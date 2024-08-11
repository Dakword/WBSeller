## [WBSeller API](docs/API.md) / Returns()

```php
$wbSellerAPI = new \Dakword\WBSeller\API($options);
$Returns = $wbSellerAPI->Returns();
```

Wildberries API / [**Возвраты покупателями**](https://openapi.wb.ru/returns/api/ru/)

| :speech_balloon: | :cloud: | [Returns()](src/API/Endpoint/Returns.php) |
| ---------------- | ------- | ----------------------------------------- |
| Заявки покупателей на возврат  | /api/v1/claims  | Returns()->**list()**    |
| Ответ на заявку покупателя     | /api/v1/claim   | Returns()->**action()**  |
