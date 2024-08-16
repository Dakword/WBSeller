## [WBSeller API](/docs/API.md) / Content()

```php
$wbSellerAPI = new \Dakword\WBSeller\API($options);
$Content = $wbSellerAPI->Content();
```

Wildberries API / [**Контент**](https://openapi.wb.ru/content/api/ru/)

| :speech_balloon: | :cloud: | [Content()](/src/API/Endpoint/Content.php) |
| ---------------- | ------- | ---------------------------------------- |
| [**Загрузка**](https://openapi.wb.ru/content/api/ru/#tag/Zagruzka) |||
| Создание КТ                  | /content/v2/cards/upload     | Content()->**createCard()**          |
| Создание нескольких КТ       | /content/v2/cards/upload     | Content()->**createCards()**         |
| Редактирование КТ            | /content/v2/cards/update     | Content()->**updateCard()**          |
| Редактирование нескольких КТ | /content/v2/cards/update     | Content()->**updateCards()**         |
| Добавление НМ к КТ           | /content/v2/cards/upload/add | Content()->**addCardNomenclature()** |
| Объединение НМ               | /content/v2/cards/moveNm     | Content()->**moveNms()**             |
| Разъединение НМ              | /content/v2/cards/moveNm     | Content()->**removeNms()**           |
| Генерация баркодов           | /content/v2/barcodes         | Content()->**generateBarcodes()**    |
| [**Просмотр**](https://openapi.wb.ru/content/api/ru/#tag/Prosmotr) |||
| Список НМ                        | /content/v2/get/cards/list   | Content()->**getCardsList()**        |
| КТ по артикулу продавца          | /content/v2/get/cards/list   | Content()->**getCardByVendorCode()** |
| КТ по артикулу WB                | /content/v2/get/cards/list   | Content()->**getCardByNmID()**       |
| Список несозданных НМ с ошибками | /content/v2/cards/error/list | Content()->**getErrorCardsList()**   |
| Лимиты                           | /content/v2/cards/limits     | Content()->**getCardsLimits()**      |
| [**Конфигуратор**](https://openapi.wb.ru/content/api/ru/#tag/Konfigurator) |||
| Список предметов (подкатегорий)        | /content/v2/object/all                | Content()->**searchCategory()**             |
| Родительские категории товаров         | /content/v2/object/parent/all         | Content()->**getParentCategories()**        |
| Характеристики предмета (подкатегории) | /content/v2/object/charcs/{subjectId} | Content()->**getCategoryCharacteristics()** |
| Значения характеристики                | /content/v2/directory/{directory}     | Content()->**getDirectory()**               |
| Цвет                                   | /content/v2/directory/colors          | Content()->**getDirectoryColors()**         |
| Пол                                    | /content/v2/directory/kinds           | Content()->**getDirectoryKinds()**          |
| Страна производства                    | /content/v2/directory/countries       | Content()->**getDirectoryCountries()**      |
| Сезон                                  | /content/v2/directory/seasons         | Content()->**getDirectorySeasons()**        |
| Ставка НДС                             | /content/v2/directory/vat             | Content()->**getDirectoryNDS()**            |
| ТНВЭД код                              | /content/v2/directory/tnved           | Content()->**searchDirectoryTNVED()**       |
| [**Медиафайлы**](https://openapi.wb.ru/content/api/ru/#tag/Mediafajly) |||
| Изменить медиафайлы | /content/v3/media/save | Content()->**updateMedia()** |
| Добавить медиафайлы | /content/v3/media/file | Content()->**uploadMedia()** |
<br>

## [WBSeller API](/docs/API.md) / Content()->Tags()

```php
$wbSellerAPI = new \Dakword\WBSeller\API($options);
$Content = $wbSellerAPI->Content();
$Tags = Content()->Tags();
```

Wildberries API Контент / [**Теги**](https://openapi.wb.ru/content/api/ru/#tag/Tegi)

| :speech_balloon: | :cloud: | [Tags()](/src/API/Endpoint/Subpoint/Tags.php) |
| ---------------- | ------- | --------------------------------------------- |
| Список тегов           | /content/v2/tags                  | Tags()->**list()**                |
| Создание тега          | /content/v2/tag/                  | Tags()->**create()**              |
| Удаление тега          | /content/v2/tag/{id}              | Tags()->**delete()**              |
| Изменение тега         | /content/v2/tag/{id}              | Tags()->**update()**              |
| Управление тегами в КТ | /content/v2/tag/nomenclature/link | Tags()->**setNomenclatureTags()** |
<br>

## [WBSeller API](/docs/API.md) / Content()->Trash()

```php
$wbSellerAPI = new \Dakword\WBSeller\API($options);
$Content = $wbSellerAPI->Content();
$Trash = Content()->Trash();
```

Wildberries API Контент / [**Корзина**](https://openapi.wb.ru/content/api/ru/#tag/Korzina)

| :speech_balloon: | :cloud: | [Trash()](/src/API/Endpoint/Subpoint/Trash.php) |
| ---------------- | ------- | ----------------------------------------------- |
| Список НМ, находящихся в корзине | /content/v2/get/cards/trash    | Trash()->**list()**    |
| Перенос НМ в корзину             | /content/v2/cards/delete/trash | Trash()->**add()**     |
| Восстановление НМ из корзины     | /content/v2/cards/recover      | Trash()->**recover()** |
<br>

## [WBSeller API](/docs/API.md) / Content()->News()

```php
$wbSellerAPI = new \Dakword\WBSeller\API($options);
$Content = $wbSellerAPI->Content();
$News = Content()->News();
```
Wildberries API / [**Новости портала поставщиков**](https://openapi.wb.ru/general/sellers_portal_news/ru/)

| :speech_balloon: | :cloud: | [News()](/src/API/Endpoint/Subpoint/News.php) |
| ---------------- | ------- | --------------------------------------------- |
| Новости с даты | /api/communications/v1/news | News()->**fromDate()** |
| Новости с ID   | /api/communications/v1/news | News()->**fromId()**   |
