## [WBSeller API](/docs/API.md) / Adv()

```php
$wbSellerAPI = new \Dakword\WBSeller\API($options);
$Adv = $wbSellerAPI->Adv();
```

Wildberries API / [**Продвиженение**](https://openapi.wb.ru/promotion/api/ru)

| :speech_balloon: | :cloud: | [Adv()](/src/API/Endpoint/Adv.php) |
| ---------------- | ------- | ---------------------------------- |
| Проверка подключения к API    | /ping                                 | Adv()->**ping()**                      |
| [**Продвижение**](https://openapi.wb.ru/promotion/api/ru/#tag/Prodvizhenie) |||
| Списки кампаний               | /adv/v1/promotion/count               | Adv()->**advertsList()**               |
| Переименование кампании       | /adv/v0/rename                        | Adv()->**renameAdvert()**              |
| Удаление кампании             | /adv/v0/delete                        | Adv()->**delete()**                    |
| Информация о кампаниях        | /adv/v1/promotion/adverts             | Adv()->**advertsInfo()**               |
| Информация о кампаниях по списку id | /adv/v1/promotion/adverts       | Adv()->**advertsInfoByIds()**          |
| Изменение ставки у кампании   | /adv/v0/cpm                           | Adv()->**updateCpm()**                 |
| [**Активность кампании**](https://openapi.wb.ru/promotion/api/ru/#tag/Aktivnost-kampanii) |||
| Запуск кампании               | /adv/v0/start                         | Adv()->**start()**                     |
| Пауза кампании                | /adv/v0/pause                         | Adv()->**pause()**                     |
| Завершение кампании           | /adv/v0/stop                          | Adv()->**stop()**                      |
| [**Словари**](https://openapi.wb.ru/promotion/api/ru/#tag/Slovari) |||
| Номенклатуры для кампаний     | /adv/v2/supplier/nms                  | Adv()->**nms()**                       |
| Предметы для кампаний         | /adv/v1/supplier/subjects             | Adv()->**subjects()**                  |
| [**Статистика**](https://openapi.wb.ru/promotion/api/ru/#tag/Statistika) |||
| Статистика кампаний           | /adv/v2/fullstats                     | Adv()->**statistic()**                 |
| Статистика по ключевым фразам | /adv/v0/stats/keywords                | Adv()->**advertStatisticByKeywords()** |
<br>

## [WBSeller API](/docs/API.md) / Adv()->Auto()

```php
$wbSellerAPI = new \Dakword\WBSeller\API($options);
$Auto = $wbSellerAPI->Adv()->Auto();
```
Wildberries API Продвижение / **Автоматическая кампания**

| :speech_balloon: | :cloud: | [Auto()](/src/API/Endpoint/Subpoint/AdvAuto.php) |
| ---------------- | ------- | ------------------------------------------------ |
| Создать автоматическую кампанию | /adv/v1/save-ad           | Auto()->**createAdvert()**              |
| [**Управление параметрами**](https://openapi.wb.ru/promotion/api/ru/#tag/Upravlenie-parametrami-avtomaticheskih-kampanij) |||
| Список номенклатур              | /adv/v1/auto/getnmtoadd   | Auto()->**getAdvertNmsToAdd()**         |
| Изменение списка номенклатур    | /adv/v1/auto/updatenm     | Auto()->**updateAdvertNms()**           |
| Управление зонами показов       | /adv/v1/auto/active       | Auto()->**setAdvertActives()**          |
| Установка минус-фраз            | /adv/v1/auto/set-excluded | Auto()->**setAdvertMinuses()**          |
| Удаление минус-фраз             | /adv/v1/auto/set-excluded | Auto()->**deleteAdvertMinuses()**       |
| [**Статистика**](https://openapi.wb.ru/promotion/api/ru/#tag/Statistika) |||
| Статистика по кластерам фраз    | /adv/v1/auto/stat-words   | Auto()->**advertStatisticByWords()**    |
| Статистика по ключевым фразам   | /adv/v0/stats/keywords    | Auto()->**advertStatisticByKeywords()** |
<br>

## [WBSeller API](/docs/API.md) / Adv()->Auction()

```php
$wbSellerAPI = new \Dakword\WBSeller\API($options);
$AuctionCatalog = $wbSellerAPI->Adv()->Auction();
```
Wildberries API Продвижение / **Кампания Аукцион** (Поиск + Каталог)

| :speech_balloon: | :cloud: | [Auction()](/src/API/Endpoint/Subpoint/AdvSearchCatalog.php) |
| ---------------- | ------- | ------------------------------------------------------------ |
| Создать кампанию Аукцион                    | /adv/v2/seacat/save-ad      | Auction()->**createAdvert()**             |
| [**Управление параметрами**](https://openapi.wb.ru/promotion/api/ru/#tag/Upravlenie-parametrami-kampanij-Aukcion) |||
| Изменение активности предметной группы      | /adv/v0/active              | Auction()->**setAdvertSubjectActive()**   |
| Управление активностью фиксированных фраз   | /adv/v1/search/set-plus     | Auction()->**setAdvertPlusesActive()**    |
| Установка фиксированных фраз                | /adv/v1/search/set-plus     | Auction()->**setAdvertPluses()**          |
| Удаление фиксированных фраз                 | /adv/v1/search/set-plus     | Auction()->**deleteAdvertPluses()**       |
| Установка минус-фраз фразового соответствия | /adv/v1/search/set-phrase   | Auction()->**setAdvertMinusPhrases()**    |
| Удаление минус-фраз фразового соответствия  | /adv/v1/search/set-phrase   | Auction()->**deleteAdvertMinusPhrases()** |
| Установка минус-фраз точного соответствия   | /adv/v1/search/set-strong   | Auction()->**setAdvertMinusStrong()**     |
| Удаление минус-фраз точного соответствия    | /adv/v1/search/set-strong   | Auction()->**deleteAdvertMinusStrong()**  |
| Установка минус-фраз из поиска              | /adv/v1/search/set-excluded | Auction()->**setAdvertMinuses()**         |
| Удаление минус-фраз из поиска               | /adv/v1/search/set-excluded | Auction()->**deleteAdvertMinuses()**      |
| [**Статистика**](https://openapi.wb.ru/promotion/api/ru/#tag/Statistika) |||
| Статистика кампаний                                   | /adv/v1/seacat/stat    | Auction()->**advertStatistic()**           |
| Статистика поисковой кампании<br>по ключевым фразам   | /adv/v1/stat/words     | Auction()->**advertStatisticByWords()**    |
| Статистика по ключевым фразам<br>для компаний Аукцион | /adv/v0/stats/keywords | Auction()->**advertStatisticByKeywords()** |
<br>

## [WBSeller API](/docs/API.md) / Adv()->Finances()

```php
$wbSellerAPI = new \Dakword\WBSeller\API($options);
$Finances = $wbSellerAPI->Adv()->Finances();
```
Wildberries API Продвижение / [**Финансы**](https://openapi.wb.ru/promotion/api/ru/#tag/Finansy)

| :speech_balloon: | :cloud: | [Finances()](/src/API/Endpoint/Subpoint/AdvFinance.php) |
| ---------------- | ------- | ------------------------------------------------------- |
| Баланс                      | /adv/v1/balance         | Finances()->**balance()**             |
| Бюджет кампании             | /adv/v1/budget          | Finances()->**getAdvertBudget()**     |
| Пополнение бюджета кампании | /adv/v1/budget/deposit  | Finances()->**depositAdvertBudget()** |
| История пополнений счета    | /adv/v1/payments        | Finances()->**payments()**            |
| История затрат              | /adv/v1/upd             | Finances()->**costs()**               |
