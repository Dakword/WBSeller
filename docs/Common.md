## [WBSeller API](/docs/API.md) / Common()

```php
$wbSellerAPI = new \Dakword\WBSeller\API($options);
$Common = $wbSellerAPI->Common();
```

Wildberries API / **Общее**

| :speech_balloon: | :cloud: | [Common()](/src/API/Endpoint/Common.php) |
| ---------------- | ------- | ---------------------------------------- |
| Проверка подключения к API   | /ping | Common()->**ping()** |
<br>

## [WBSeller API](/docs/API.md) / Common()->News()

```php
$wbSellerAPI = new \Dakword\WBSeller\API($options);
$Common = $wbSellerAPI->Common();
$News = $Common->News();
```
Wildberries API / [**Новости портала поставщиков**](https://openapi.wb.ru/general/sellers_portal_news/ru/)

| :speech_balloon: | :cloud: | [News()](/src/API/Endpoint/Subpoint/News.php) |
| ---------------- | ------- | --------------------------------------------- |
| Новости с даты | /api/communications/v1/news | News()->**fromDate()** |
| Новости с ID   | /api/communications/v1/news | News()->**fromId()**   |
