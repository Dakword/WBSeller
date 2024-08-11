## [WBSeller API](docs/API.md) / Chat()

```php
$wbSellerAPI = new \Dakword\WBSeller\API($options);
$Chat = $wbSellerAPI->Chat();
```

Wildberries API / [**Чат с покупателями**](https://openapi.wb.ru/buyers-chat/api/ru/)

| :speech_balloon: | :cloud: | [Returns()](src/API/Endpoint/Chat.php) |
| ---------------- | ------- | ----------------------------------------- |
| Список чатов         | /api/v1/seller/chats    | Chat()->**list()**     |
| События чатов        | /api/v1/seller/events   | Chat()->**events()**   |
| Отправить сообщение  | /api/v1/seller/message  | Chat()->**message()**  |
