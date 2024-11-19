## [WBSeller API](/docs/API.md) / Questions()

```php
$wbSellerAPI = new \Dakword\WBSeller\API($options);
$Questions = $wbSellerAPI->Questions();
```

Wildberries API / [**Вопросы**](https://openapi.wb.ru/feedbacks-questions/api/ru/)

| :speech_balloon: | :cloud: | [Questions()](/src/API/Endpoint/Questions.php) |
| ---------------- | ------- | --------------------------------------------- |
| Проверка подключения к API        | /ping                                 | Questions()->**ping()**                    |
| Наличие непросмотренных вопросов  | /api/v1/new-feedbacks-questions       | Questions()->**hasNew()**                  |
| Количество необработанных вопросов<br>за период | /api/v1/questions/count | Questions()->**unansweredCountByPeriod()** |
| Количество обработанных вопросов<br>за период   | /api/v1/questions/count | Questions()->**answeredCountByPeriod()**   |
| Неотвеченные вопросы              | /api/v1/questions/count-unanswered    | Questions()->**unansweredCount()**         |
| Список вопросов                   | /api/v1/questions                     | Questions()->**list()**                    |
| Получить вопрос по id             | /api/v1/questions                     | Questions()->**get()**                     |
| Просмотреть вопрос                | /api/v1/questions                     | Questions()->**changeViewed()**            |
| Ответить на вопрос                | /api/v1/questions                     | Questions()->**sendAnswer()**              |
| Отклонить вопрос                  | /api/v1/questions                     | Questions()->**reject()**                  |
| Получение вопросов в формате XLSX | /api/v1/questions/report              | Questions()->**xlsReport()**               |
<br>

## [WBSeller API](docs/API.md) / Questions()->Templates()
```php
$wbSellerAPI = new \Dakword\WBSeller\API($options);
$Questions = $wbSellerAPI->Questions();
$Templates = $Questions()->Templates();
```

Wildberries API Вопросы / [**Шаблоны для вопросов**](https://openapi.wb.ru/feedbacks-questions/api/ru/#tag/Shablony-dlya-voprosov-i-otzyvov)

| :speech_balloon: | :cloud: | [Templates()](/src/API/Endpoint/Subpoint/Templates.php) |
| ---------------- | ------- | ------------------------------------------------------ |
| Cписок шаблонов  | /api/v1/templates | Templates()->**list()**   |
| Создать шаблон   | /api/v1/templates | Templates()->**create()** |
| Обновить шаблон  | /api/v1/templates | Templates()->**update()** |
| Удалить шаблон   | /api/v1/templates | Templates()->**delete()** |
