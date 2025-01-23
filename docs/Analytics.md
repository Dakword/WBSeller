## [WBSeller API](/docs/API.md) / Analytics()

```php
$wbSellerAPI = new \Dakword\WBSeller\API($options);
$Analytics = $wbSellerAPI->Analytics();
```

Wildberries API / [**Аналитика**](https://openapi.wb.ru/analytics/api/ru/)

| :speech_balloon: | :cloud: | [Analytics()](/src/API/Endpoint/Analytics.php) |
| ---------------- | ------- | ---------------------------------------------- |
| Проверка подключения к API | /ping | Analytics()->**ping()** |
| [**Воронка продаж**](https://openapi.wb.ru/analytics/api/ru/#tag/Voronka-prodazh) |||
| Получение статистики КТ за выбранный период,<br>по nmID/предметам/брендам/тегам             | /api/v2/nm-report/detail          | Analytics()->**nmReportDetail()**         |
| Получение статистики КТ за период,<br>сгруппированный по предметам, брендам и тегам         | /api/v2/nm-report/grouped         | Analytics()->**nmReportGrouped()**        |
| Получение статистики КТ по дням<br>по выбранным nmID                                        | /api/v2/nm-report/detail/history  | Analytics()->**nmReportDetailHistory()**  |
| Получение статистики КТ по дням за период,<br>сгруппированный по предметам, брендам и тегам | /api/v2/nm-report/grouped/history | Analytics()->**nmReportGroupedHistory()** |
| [**Товары с обязательной маркировкой**](https://openapi.wb.ru/analytics/api/ru/#tag/Tovary-s-obyazatelnoj-markirovkoj) |||
| Отчёт по товарам с обязательной маркировкой | /api/v1/analytics/excise-report | Analytics()->**exciseReport()** |
| [**Платная приемка**](https://openapi.wb.ru/analytics/api/ru/#tag/Platnaya-priyomka) |||
| Отчет о платной приемке | /api/v1/analytics/acceptance-report | Analytics()->**acceptanceReport()** |
| [**Отчеты по удержаниям**](https://openapi.wb.ru/analytics/api/ru/#tag/Otchyoty-po-uderzhaniyam) |||
| Самовыкупы                          | /api/v1/analytics/antifraud-details      | Analytics()->**antifraudDetails()**      |
| Подмена товара                      | /api/v1/analytics/incorrect-attachments  | Analytics()->**incorrectAttachments()**  |
| Коэффициент логистики<br>и хранения | /api/v1/analytics/storage-coefficient    | Analytics()->**storageCoefficient()**    |
| Маркировка товара                   | /api/v1/analytics/goods-labeling         | Analytics()->**goodsLabeling()**         |
| Смена характеристик                 | /api/v1/analytics/characteristics-change | Analytics()->**characteristicsChange()** |
| [**Продажи по регионам**](https://openapi.wb.ru/analytics/api/ru/#tag/Prodazhi-po-regionam) |||
| Отчет о продажах сгруппированный<br>по регионам стран | /api/v1/analytics/region-sale | Analytics()->**regionSale()** |
| [**Отчёт по возвратам товаров**](https://openapi.wb.ru/analytics/api/ru/#tag/Otchyot-po-vozvratam-tovarov) |||
| Получить отчет | /api/v1/analytics/goods-return | Analytics()->**goodsReturn()** |
| **Динамика оборачиваемости** |||
| Ежедневная динамика | /api/v1/turnover-dynamics/daily-dynamics | Analytics()->**dailyDynamics()** |
<br>

## [WBSeller API](/docs/API.md) / Analytics()->PaidStorage()

```php
$wbSellerAPI = new \Dakword\WBSeller\API($options);
$PaidStorage = $wbSellerAPI->Analytics()->PaidStorage();
```
Wildberries API Аналитика / [**Платное хранение**](https://openapi.wb.ru/analytics/api/ru/#tag/Platnoe-hranenie)

| :speech_balloon: | :cloud: | [PaidStorage()](/src/API/Endpoint/Subpoint/PaidStorage.php) |
| ---------------- | ------- | ----------------------------------------------------------- |
| Создать отчёт    | /api/v1/paid_storage                         | PaidStorage()->**makeReport()**        |
| Проверить статус | /api/v1/paid_storage/tasks/{taskId}/status   | PaidStorage()->**checkReportStatus()** |
| Получить отчёт   | /api/v1/paid_storage/tasks/{taskId}/download | PaidStorage()->**getReport()**         |
<br>

## [WBSeller API](/docs/API.md) / Analytics()->Brands()

```php
$wbSellerAPI = new \Dakword\WBSeller\API($options);
$Brands = $wbSellerAPI->Analytics()->Brands();
```
Wildberries API Аналитика / [**Доля бренда в продажах**](https://openapi.wb.ru/analytics/api/ru/#tag/Dolya-brenda-v-prodazhah)

| :speech_balloon: | :cloud: | [Brands()](/src/API/Endpoint/Subpoint/Brands.php) |
| ---------------- | ------- | ------------------------------------------------- |
| Бренды продавца                 | /api/v1/analytics/brand-share/brands          | Brands()->**getBrands()**              |
| Родительские категории бренда   | /api/v1/analytics/brand-share/parent-subjects | Brands()->**getBrandParentSubjects()** |
| Отчёт по доле бренда в продажах | /api/v1/analytics/brand-share                 | Brands()->**getReport()**              |
<br>

## [WBSeller API](/docs/API.md) / Analytics()->WarehouseRemains()

```php
$wbSellerAPI = new \Dakword\WBSeller\API($options);
$WarehouseRemains = $wbSellerAPI->Analytics()->WarehouseRemains();
```
Wildberries API Аналитика / [**Остатки на складах**](https://openapi.wb.ru/analytics/api/ru/#tag/Otchyot-po-ostatkam-na-skladah)

| :speech_balloon: | :cloud: | [WarehouseRemains()](/src/API/Endpoint/Subpoint/WarehouseRemains.php) |
| ---------------- | ------- | --------------------------------------------------------------------- |
| Создать отчёт    | /api/v1/warehouse_remains                         | WarehouseRemains()->**makeReport()**        |
| Проверить статус | /api/v1/warehouse_remains/tasks/{taskId}/status   | WarehouseRemains()->**checkReportStatus()** |
| Получить отчёт   | /api/v1/warehouse_remains/tasks/{taskId}/download | WarehouseRemains()->**getReport()**         |
<br>

## [WBSeller API](/docs/API.md) / Analytics()->BannedProducts()

```php
$wbSellerAPI = new \Dakword\WBSeller\API($options);
$BannedProducts = $wbSellerAPI->Analytics()->BannedProducts();
```
Wildberries API Аналитика / [**Скрытые товары**](https://openapi.wildberries.ru/analytics/api/ru/#tag/Skrytye-tovary)

| :speech_balloon: | :cloud: | [BannedProducts()](/src/API/Endpoint/Subpoint/BannedProducts.php) |
| ---------------- | ------- | ----------------------------------------------------------------- |
| Заблокированные карточки | /api/v1/analytics/banned-products/blocked  | BannedProducts()->**blocked()**  |
| Скрытые из каталога      | /api/v1/analytics/banned-products/shadowed | BannedProducts()->**shadowed()** |
