## [WBSeller API](/docs/API.md) / Feedbacks()

```php
$wbSellerAPI = new \Dakword\WBSeller\API($options);
$Feedbacks = $wbSellerAPI->Feedbacks();
```

Wildberries API / [**Отзывы**](https://openapi.wb.ru/feedbacks-questions/api/ru/)

| :speech_balloon: | :cloud: | [Feedbacks()](/src/API/Endpoint/Feedbacks.php) |
| ---------------- | ------- | --------------------------------------------- |
| Проверка подключения к API       | /ping                              | Feedbacks()->**ping()**            |
| Наличие непросмотренных отзывов  | /api/v1/new-feedbacks-questions    | Feedbacks()->**hasNew()**          |
| Количество отзывов               | /api/v1/feedbacks/count            | Feedbacks()->**count()**           |
| Необработанные отзывы            | /api/v1/feedbacks/count-unanswered | Feedbacks()->**unansweredCount()** |
| Список отзывов                   | /api/v1/feedbacks                  | Feedbacks()->**list()**            |
| Получить отзыв по Id             | /api/v1/feedbacks                  | Feedbacks()->**get()**             |
| Ответить на отзыв                | /api/v1/feedbacks/answer           | Feedbacks()->**sendAnswer()**      |
| Отредактировать ответ на отзыв   | /api/v1/feedbacks/answer           | Feedbacks()->**updateAnswer()**    |
| Пожаловаться на отзыв            | /api/v1/feedbacks/actions          | Feedbacks()->**rateFeedback()**    |
| Сообщить о проблеме с товаром    | /api/v1/feedbacks/actions          | Feedbacks()->**rateProduct()**     |
| Пожаловаться на отзый,<br>сообщить о проблеме с товаром | /api/v1/feedbacks/actions | Feedbacks()->**rate()**            |
| Список архивных отзывов          | /api/v1/feedbacks/archive          | Feedbacks()->**archive()**         |
| Получить списки причин<br>жалоб и проблем с товаром | /api/v1/supplier-valuations | Feedbacks()->**ratesList()**       |
| Получение отзывов в формате XLSX | /api/v1/feedbacks/report           | Feedbacks()->**xlsReport()**       |
| Возврат товара по ID отзыва      | /api/v1/feedbacks/order/return     | Feedbacks()->**orderReturn()**     |
<br>

## [WBSeller API](/docs/API.md) / Feedbacks()->Templates()
```php
$wbSellerAPI = new \Dakword\WBSeller\API($options);
$Feedbacks = $wbSellerAPI->Feedbacks();
$Templates = $Feedbacks()->Templates();
```

Wildberries API Отзывы / [**Шаблоны для отзывов**](https://openapi.wb.ru/feedbacks-questions/api/ru/#tag/Shablony-dlya-voprosov-i-otzyvov)

| :speech_balloon: | :cloud: | [Templates()](/src/API/Endpoint/Subpoint/Templates.php) |
| ---------------- | ------- | ------------------------------------------------------ |
| Cписок шаблонов  | /api/v1/templates | Templates()->**list()**   |
| Создать шаблон   | /api/v1/templates | Templates()->**create()** |
| Обновить шаблон  | /api/v1/templates | Templates()->**update()** |
| Удалить шаблон   | /api/v1/templates | Templates()->**delete()** |
