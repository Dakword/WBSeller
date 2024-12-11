## [WBSeller API](/docs/API.md) / Supplies()

```php
$wbSellerAPI = new \Dakword\WBSeller\API($options);
$Supplies = $wbSellerAPI->Supplies();
```

Wildberries API / [**Поставки**](https://openapi.wb.ru/supplies/api/ru/)

| :speech_balloon: | :cloud: | [Supplies()](/src/API/Endpoint/Supplies.php) |
| ---------------- | ------- | -------------------------------------------- |
| Проверка подключения к API | /ping                           | Supplies()->**ping()**         |
| [**Информация для формирования поставок**](https://openapi.wb.ru/supplies/api/ru/#tag/Informaciya-dlya-formirovaniya-postavok) |||
| Коэффициенты приёмки       | /api/v1/acceptance/coefficients | Supplies()->**coefficients()** |
| Опции приёмки              | /api/v1/acceptance/options      | Supplies()->**options()**      |
| Список складов             | /api/v1/warehouses              | Supplies()->**warehouses()**   |
