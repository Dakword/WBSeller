## WBSeller API
Библиотека для работы с [Wildberries API](https://openapi.wb.ru)

```php
$wbSellerAPI = new \Dakword\WBSeller\API($options = [
    'masterkey' => 'token',
    'keys' => [
        'content' => 'content_token'
    ],
    //'apiurls' => [
    //    'content' => 'https://suppliers-api.wb.ru'
    //],
    //'locale' => 'ru'
]);
```

### Поддерживаемые API

| API | Endpoint | $options['keys' / 'apiurls']['?'] | 'apiurls' defaults |
| --- | -------- | --------------------------------- | ------------------ |
| Общее                    | $wbSellerAPI->[**Common()**](Common.md)                 | сommon      | https://common-api.wildberries.ru
| Контент                  | $wbSellerAPI->[**Content()**](/docs/Content.md)         | content     | https://suppliers-api.wildberries.ru
| Цены и скидки            | $wbSellerAPI->[**Prices()**](/docs/Prices.md)           | prices      | https://discounts-prices-api.wildberries.ru
| Маркетплейс              | $wbSellerAPI->[**Marketplace()**](/docs/Marketplace.md) | marketplace | https://marketplace-api.wildberries.ru
| Статистика               | $wbSellerAPI->[**Statistics()**](/docs/Statistics.md)   | statistics  | https://statistics-api.wildberries.ru
| Аналитика                | $wbSellerAPI->[**Analytics()**](/docs/Analytics.md)     | analytics   | https://seller-analytics-api.wildberries.ru
| Продвижение              | $wbSellerAPI->[**Adv()**](/docs/Adv.md)                 | adv         | https://advert-api.wildberries.ru
| Рекомендации             | $wbSellerAPI->[**Recommends()**](Recommends.md)         | recommends  | https://recommend-api.wildberries.ru
| Вопросы                  | $wbSellerAPI->[**Questions()**](Questions.md)           | feedbacks   | https://feedbacks-api.wildberries.ru
| Отзывы                   | $wbSellerAPI->[**Feedbacks()**](Feedbacks.md)           | feedbacks   | https://feedbacks-api.wildberries.ru
| Тарифы                   | $wbSellerAPI->[**Tariffs()**](Tariffs.md)               | tariffs     | https://common-api.wildberries.ru
| Чат<br>с покупателями    | $wbSellerAPI->[**Chat()**](Chat.md)                     | chat        | https://buyer-chat-api.wildberries.ru
| Возвраты<br>покупателями | $wbSellerAPI->[**Returns()**](Returns.md)               | returns     | https://returns-api.wildberries.ru
| Документы                | $wbSellerAPI->[**Documents()**](Documents.md)           | documents   | https://documents-api.wildberries.ru
