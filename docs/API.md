## WBSeller API
Библиотека для работы с [Wildberries API](https://openapi.wb.ru)

```php
$wbSellerAPI = new \Dakword\WBSeller\API($options = [
    'masterkey' => 'token',
    'keys' => [
        'content' => 'content_token'
    ],
    //'apiurls' => [...],
    //'locale' => 'ru'
]);
```

### Поддерживаемые API

| API | Endpoint | $options['keys' / 'apiurls']['?'] | 'apiurls' defaults |
| --- | -------- | --------------------------------- | ------------------ |
| Контент                  | $wbSellerAPI->**Content()**           | content           | https://suppliers-api.wildberries.ru
| Цены и скидки            | $wbSellerAPI->**Prices()**            | prices            | https://discounts-prices-api.wildberries.ru
| Маркетплейс              | $wbSellerAPI->**Marketplace()**       | marketplace       | https://marketplace-api.wildberries.ru
| Статистика               | $wbSellerAPI->**Statistic()**         | statistics        | https://statistics-api.wildberries.ru
| Аналитика                | $wbSellerAPI->**Analitics()**         | analytics         | https://seller-analytics-api.wildberries.ru
| Продвижение              | $wbSellerAPI->**Adv()**               | adv               | https://advert-api.wildberries.ru
| Рекомендации             | $wbSellerAPI->**Recommendations()**   | recommendations   | https://recommend-api.wildberries.ru
| Вопросы                  | $wbSellerAPI->[**Questions()**](docs/Questions.md) | feedbacks         | https://feedbacks-api.wildberries.ru
| Отзывы                   | $wbSellerAPI->[**Feedbacks()**](docs/Feedbacks.md) | feedbacks         | https://feedbacks-api.wildberries.ru
| Тарифы                   | $wbSellerAPI->[**Tariffs()**](docs/Tariffs.md)     | tariffs           | https://common-api.wildberries.ru
| Чат<br>с покупателями    | $wbSellerAPI->[**Chat()**](docs/Chat.md)           | chat              | https://buyer-chat-api.wildberries.ru
| Возвраты<br>покупателями | $wbSellerAPI->[**Returns()**](docs/Returns.md)     | returns           | https://returns-api.wildberries.ru
| Документы                | $wbSellerAPI->[**Documents()**](docs/Documents.md) | documents         | https://documents-api.wildberries.ru
