## [WBSeller API](/docs/API.md) / Statistics()

```php
$wbSellerAPI = new \Dakword\WBSeller\API($options);
$Statistics = $wbSellerAPI->Statistics();
```

Wildberries API / [**Статистика**](https://openapi.wb.ru/statistics/api/ru/)

| :speech_balloon: | :cloud: | [Statistics()](/src/API/Endpoint/Statistics.php) |
| ---------------- | ------- | ------------------------------------------------ |
| Проверка подключения к API     | /ping                                 | Statistics()->**ping()**           |
| Поставки                       | /api/v1/supplier/incomes              | Statistics()->**incomes()**        |
| Остатки на складах             | /api/v1/supplier/stocks               | Statistics()->**stocks()**         |
| Заказы                         | /api/v1/supplier/orders               | Statistics()->**ordersFromDate()** |
| Заказы за дату                 | /api/v1/supplier/orders               | Statistics()->**ordersOnDate()**   |
| Продажи и возвраты             | /api/v1/supplier/sales                | Statistics()->**salesFromDate()**  |
| Продажи и возвраты за дату     | /api/v1/supplier/sales                | Statistics()->**salesOnDate()**    |
| Отчет о продажах по реализации | /api/v5/supplier/reportDetailByPeriod | Statistics()->**detailReport()**   |
