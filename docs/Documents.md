## [WBSeller API](docs/API.md) / Documents()

```php
$wbSellerAPI = new \Dakword\WBSeller\API($options);
$Documents = $wbSellerAPI->Documents();
```

Wildberries API [**Документы**](https://openapi.wb.ru/documents/api/ru/)

| :speech_balloon: | :cloud: | [Documents()](src/API/Endpoint/Documents.php) |
| ---------------- | ------- | --------------------------------------------- |
| Категории документов  | /api/v1/documents/categories    | Documents()->**categories()**    |
| Список документов     | /api/v1/documents/list          | Documents()->**list()**          |
| Получить документ     | /api/v1/documents/download      | Documents()->**get()**           |
| Получить документы    | /api/v1/documents/download/all  | Documents()->**getDocuments()**  |