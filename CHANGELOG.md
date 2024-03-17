### 4.3.0
* Удалено API Промо: `Promo()`
* Новая версия API Цены: `Prices()`
* Новые методы API Аналитика: `exciseReport()`, `nmReportDetailHistory()`, `nmReportGroupedHistory()`

### 4.2.0
* Новое API Аналитика: `Analytics()`
* Новые методы API Контента: Новости `Content()->News()`
* Изменения в параметрах метода `Content::updateMedia()`
* Изменения в параметрах метода `Content::uploadMedia()`
* Удален метод `Statistics::exciseGoods()`

### 4.1.0
* Новое API Тарифы: `Tariffs()`
* Удалена константа API::WB_API_VERSION

### 4.0.1
* Новые методы API Отзывов: `ratesList()`, `rate()`, `rateFeedback()`, `rateProduct()`

### 4.0.0
* Новая версия API Контента
* Персональные ключи для каждого API при создании класса (<b>keys</b>).<br/>
  Один универсальный - <b>masterkey</b>. Будет использован при отсутствии персонального.
* Удален класс `Query`
* Изменения в параметрах метода `Content::addCardNomenclature()`
* Изменения в параметрах метода `Content::searchCategory()`
* Изменения в параметрах метода `Content::getCategoryCharacteristics()`
* Изменения в параметрах метода `Content::searchDirectoryTNVED`
* Новый метод `Content::getDirectoryNDS()`
* Новый метод `Content::getCardByNmID()`
* Удален метод `Content::getCategoriesCharacteristics()`
* Переименование метода `Content::updateCards()` в `Content::updateCard()`
* Переименование метода `Content::getCardsByVendorCodes()` в `Content::getCardByVendorCode()`
* Выделение методов работы с корзиной в подкласс `Content()->Trash()`
* Новые методы работы с корзиной `Trash::add()`, `Trash::recover()`
* Использование переменной окружения WBSELLER_LOCALE для выбора языка ответа (ru, en, zh)

### 3.14.1
* Обработка нового ответа с кодом 429

### 3.14.0
* Удален метод `Content::searchDirectoryBrands()`

### 3.13.0
* Удалены методы API Marketplace: `confirmOrder()`, `deliverOrder()`, `receiveOrder()`, `rejectOrder()`
* Новые методы API Marketplace:
`setOrderUin()`, `setOrderIMEI()`, `setOrderGTIN()`, `getOrderMeta()`, `deleteOrderMeta()`, `getOrdersExternalStickers()`,
`getSupplyBoxes()`, `addSupplyBoxes()`, `deleteSupplyBoxes()`, `addBoxOrders()`, `deleteBoxOrder()`, `getSupplyBoxStickers()`
* Удалены методы API Feedbacks: `createComplaint()`
* Новый метод API Feedbacks: `count()`
* Изменения в параметрах методов: `Feedbacks::list()`, `Feedbacks::archive()`, `Feedbacks::xlsReport()`

### 3.12.1
* Новый метод API вопросов: `Questions::get()`
* Новый метод API отзывов: `Feedbacks::get()`

### 3.12.0
* Добавлено API Questions/Шаблоны
* Добавлено API Feedbacks/Шаблоны

### 3.11.0
* Новый метод API контента: `Content::removeNms()`
* Новый метод API рекламы: `Adv::balance()`
* Новые методы API вопросов: `Questions::unansweredCountByPeriod()`, `Questions::answeredCountByPeriod()`
* Новые методы API отзывов: `Feedbacks::unansweredCountByPeriod()`, `Feedbacks::answeredCountByPeriod()`

### 3.10.0
* Добавлено API Marketplace/Пропуска

### 3.9.0
* Новые методы API Marketplace: Склады
* Выделение методов работы со складами в подкласс `Marketplace()->Warehouses()`
* Новый метод API контента: `Content::moveNms()`

### 3.8.0
* Изменения метода `Marketplace::getSupplyBarcode()`

### 3.7.0
* Новые методы API контента: Теги, Корзина, Лимиты
* Удалены методы получения значений характиристик: Коллекции, Состав, Комплектация
* Добавлен метод `Recommendations::update()`
* Выделение методов управления тегами в подкласс `Content()->Tags()`

### 3.6.0
* Удален метод `Prices::getPricesNoStock()`

### 3.5.3
* Добавлено API вопросов

### 3.5.2
* Добавлено API отзывов

### 3.5.1
* Новые методы API рекламы

### 3.5.0
* Изменения метода `Recommendations::list()`

### 3.4.0
* Новое исключение ApiTimeRestrictionsException

### 3.3.0
* Добавлено API рекомендаций

### 3.2.1
* Исправлен метод `Marketplace::getOrdersStickers()`

### 3.2.0
* Добавлено API рекламы

### 3.1.0
* Добавлен метод `AbstractEndpoint::retryOnTooManyRequests()`

### 3.0.1
* Добавлены методы:
`Marketplace::confirmOrder()` `Marketplace::deliverOrder()` `Marketplace::receiveOrder()`  `Marketplace::rejectOrder()`

### 3.0.0
* Переход на Marketplace V3