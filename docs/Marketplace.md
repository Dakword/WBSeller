## [WBSeller API](/docs/API.md) / Marketplace()

```php
$wbSellerAPI = new \Dakword\WBSeller\API($options);
$Marketplace = $wbSellerAPI->Marketplace();
```

Wildberries API / [**Маркетплейс**](https://openapi.wb.ru/marketplace/api/ru/)

| :speech_balloon: | :cloud: | [Marketplace()](/src/API/Endpoint/Marketplace.php) |
| ---------------- | ------- | -------------------------------------------------- |
| Проверка подключения к API                | /ping                                   | Marketplace()->**ping()**              |
| [**Сборочные задания**](https://openapi.wb.ru/marketplace/api/ru/#tag/Sborochnye-zadaniya) |||
| Получить список новых сборочных заданий   | /api/v3/orders/new                      | Marketplace()->**getNewOrders()**      |
| Получить информацию по сборочным заданиям | /api/v3/orders                          | Marketplace()->**getOrders()**         |
| Получить статусы сборочных заданий        | /api/v3/orders/status                   | Marketplace()->**getOrdersStatuses()** |
| Получить этикетки для сборочных заданий   | /api/v3/orders/stickers                 | Marketplace()->**getOrdersStickers()** |
| Отменить сборочное задание                | /api/v3/orders/{orderId}/cancel         | Marketplace()->**cancelOrder()**       |
| Получить метаданные сборочного задания    | /api/v3/orders/{orderId}/meta           | Marketplace()->**getOrderMeta()**      |
| Удалить метаданные сборочного задания     | /api/v3/orders/{orderId}/meta           | Marketplace()->**deleteOrderMeta()**   |
| Закрепить за сборочным заданием КиЗ       | /api/v3/orders/{orderId}/meta/sgtin     | Marketplace()->**setOrderKiz()**       |
| Закрепить за сборочным заданием УИН       | /api/v3/orders/{orderId}/meta/uin       | Marketplace()->**setOrderUin()**       |
| Закрепить за сборочным заданием IMEI      | /api/v3/orders/{orderId}/meta/imei      | Marketplace()->**setOrderIMEI()**      |
| Закрепить за сборочным заданием GTIN      | /api/v3/orders/{orderId}/meta/gtin      | Marketplace()->**setOrderGTIN()**      |
| Получить все сборочные задания на повторную отгрузку | /api/v3/supplies/orders/reshipment | Marketplace()->**getReShipmentOrdersSupplies()** |
| Получить список коробов поставки  | /api/v3/supplies/{supplyId}/trbx          | Marketplace()->**getSupplyBoxes()**          |
| Добавить короба к поставке        | /api/v3/supplies/{supplyId}/trbx          | Marketplace()->**addSupplyBoxes()**          |
| Удалить короба из поставки        | /api/v3/supplies/{supplyId}/trbx          | Marketplace()->**deleteSupplyBoxes()**       |
| Добавить заказы к коробу          | /api/v3/supplies/{supplyId}/trbx/{boxId}  | Marketplace()->**addBoxOrders()**            |
| Удалить заказ из короба           | /api/v3/supplies/{supplyId}/trbx/{boxId}/orders/{orderId} | Marketplace()->**deleteBoxOrder()** |
| Получить стикеры коробов поставки | /api/v3/supplies/{supplyId}/trbx/stickers | Marketplace()->**getSupplyBoxStickers()**    |
| Информация по клиенту             | /api/v3/orders/client                     | Marketplace()->**getOrdersClient()**         |
| [**Поставки**](https://openapi.wb.ru/marketplace/api/ru/#tag/Postavki) |||
| Список поставок                       | /api/v3/supplies                            | Marketplace()->**getSuppliesList()**  |
| Создать новую поставку                | /api/v3/supplies                            | Marketplace()->**createSupply()**     |
| Получить информацию о поставке        | /api/v3/supplies/{supplyId}                 | Marketplace()->**getSupply()**        |
| Удалить поставку                      | /api/v3/supplies/{supplyId}                 | Marketplace()->**deleteSupply()**     |
| Получить сборочные задания в поставке | /api/v3/supplies/{supplyId}/orders          | Marketplace()->**getSupplyOrders()**  |
| Добавить к поставке сборочное задание | /api/v3/supplies/{supplyId}/orders/{orderId}| Marketplace()->**addSupplyOrder()**   |
| Передать поставку в доставку          | /api/v3/supplies/{supplyId}/deliver         | Marketplace()->**closeSupply()**      |
| QR поставки                           | /api/v3/supplies/{supplyId}/barcode         | Marketplace()->**getSupplyBarcode()** |
| [**Остатки**](https://openapi.wb.ru/marketplace/api/ru/#tag/Ostatki) |||
| Получить остатки товаров | /api/v3/stocks/{warehouseId} | Marketplace()->**getWarehouseStocks()**    |
| Обновить остатки товаров | /api/v3/stocks/{warehouseId} | Marketplace()->**updateWarehouseStocks()** |
| Удалить остатки товаров  | /api/v3/stocks/{warehouseId} | Marketplace()->**deleteWarehouseStocks()** |
<br>

## [WBSeller API](/docs/API.md) / Marketplace()->Warehouses()

```php
$wbSellerAPI = new \Dakword\WBSeller\API($options);
$Marketplace = $wbSellerAPI->Marketplace();
$Warehouses = $Marketplace->Warehouses();
```
Wildberries API Маркетплейс / [**Склады**](https://openapi.wb.ru/marketplace/api/ru/#tag/Sklady)

| :speech_balloon: | :cloud: | [Warehouses()](/src/API/Endpoint/Subpoint/Warehouses.php)   |
| ---------------- | ------- | ----------------------------------------------------------- |
| Cписок складов WB       | /api/v3/offices                  | Warehouses()->**offices()** |
| Cписок складов продавца | /api/v3/warehouses               | Warehouses()->**list()**    |
| Создать склад продавца  | /api/v3/warehouses               | Warehouses()->**create()**  |
| Обновить склад продавца | /api/v3/warehouses/{warehouseId} | Warehouses()->**update()**  |
| Удалить склад продавца  | /api/v3/warehouses/{warehouseId} | Warehouses()->**delete()**  |
<br>

## [WBSeller API](/docs/API.md) / Marketplace()->Passes()

```php
$wbSellerAPI = new \Dakword\WBSeller\API($options);
$Marketplace = $wbSellerAPI->Marketplace();
$Passes = $Marketplace->Passes();
```
Wildberries API Маркетплейс / [**Пропуска**](https://openapi.wb.ru/marketplace/api/ru/#tag/Propuska)

| :speech_balloon: | :cloud: | [Passes()](/src/API/Endpoint/Subpoint/Passes.php)   |
| ---------------- | ------- | ----------------------------------------------------------- |
| Cписок складов,<br>для которых требуется пропуск | /api/v3/passes/offices | Warehouses()->**offices()** |
| Cписок пропусков | /api/v3/passes      | Warehouses()->**list()**   |
| Создать пропуск  | /api/v3/passes      | Warehouses()->**create()** |
| Обновить пропуск | /api/v3/passes/{Id} | Warehouses()->**update()** |
| Удалить пропуск  | /api/v3/passes/{Id} | Warehouses()->**delete()** |
<br>

## [WBSeller API](/docs/API.md) / Marketplace()->CrossBorder()

```php
$wbSellerAPI = new \Dakword\WBSeller\API($options);
$Marketplace = $wbSellerAPI->Marketplace();
$CrossBorder = $Marketplace->CrossBorder();
```

| :speech_balloon: | :cloud: | [CrossBorder()](/src/API/Endpoint/Subpoint/CrossBorder.php) |
| ---------------- | ------- | ----------------------------------------------------------- |
| Получить список ссылок на этикетки | /api/v3/files/orders/external-stickers | Warehouses()->**getOrdersStickers()**      |
| История статусов                   | /api/v3/orders/status/history          | Warehouses()->**getOrdersStatusHistory()** |
| Информация по клиенту              | /api/v3/orders/client                  | Warehouses()->**getOrdersClient()**        |
<br>

## [WBSeller API](/docs/API.md) / Marketplace()->DBS()

```php
$wbSellerAPI = new \Dakword\WBSeller\API($options);
$Marketplace = $wbSellerAPI->Marketplace();
$DBS = $Marketplace->DBS();
```
Wildberries API Маркетплейс / [**Доставка силами продавца**](https://openapi.wb.ru/marketplace/api/ru/#tag/Dostavka-silami-prodavca-(DBS))

| :speech_balloon: | :cloud: | [DBS()](/src/API/Endpoint/Subpoint/DBS.php) |
| ---------------- | ------- | ------------------------------------------- |
| Перевести на сборку                                  | /api/v3/orders/{orderId}/confirm | DBS()->**confirm()**         |
| Перевести в доставку                                 | /api/v3/orders/{orderId}/deliver | DBS()->**deliver()**         |
| Сообщить, что сборочное задание принято клиентом     | /api/v3/orders/{orderId}/receive | DBS()->**receive()**         |
| Сообщить, что клиент отказался от сборочного задания | /api/v3/orders/{orderId}/reject  | DBS()->**reject()**          |
| Информация по клиенту                                | /api/v3/orders/client            | DBS()->**getOrdersClient()** |

## [WBSeller API](/docs/API.md) / Marketplace()->WBGO()

```php
$wbSellerAPI = new \Dakword\WBSeller\API($options);
$Marketplace = $wbSellerAPI->Marketplace();
$WBGO = $Marketplace->WBGO();
```
Wildberries API Маркетплейс / [**Доставка курьером WB**](https://openapi.wb.ru/marketplace/api/ru/#tag/Dostavka-kurerom-WB-(WBGO))

| :speech_balloon: | :cloud: | [WBGO()](/src/API/Endpoint/Subpoint/WBGO.php) |
| ---------------- | ------- | --------------------------------------------- |
| Перевести на сборку       | /api/v3/orders/{orderId}/confirm          | WBGO()->**confirm()**        |
| Перевести в доставку      | /api/v3/orders/{orderId}/assemble         | WBGO()->**assemble()**       |
| Список контактов          | /api/v3/warehouses/{warehouseId}/contacts | WBGO()->**getContacts()**    |
| Обновить список контактов | /api/v3/warehouses/{warehouseId}/contacts | WBGO()->**updateContacts()** |
