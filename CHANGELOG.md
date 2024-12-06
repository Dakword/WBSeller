### 4.27.0 - 03/12/2024
* Новый метод `Prices::uploadClubDiscount()` - Установить скидки WB Клуба

### 4.26.0 - 23/11/2024
* Новый метод `DBS::getNewOrders()` - Получить список новых сборочных заданий
* Новый метод `DBS::getOrders()` - Получить информацию по завершенным сборочным заданиям
* Новый метод `DBS::getOrdersStatuses()` - Получить статусы сборочных заданий
* Новый метод `DBS::cancelOrder()` - Отменить сборочное задание
* Новый метод `DBS::getOrderMeta()` - Получить метаданные сборочного задания
* Новый метод `DBS::deleteOrderMeta()` - Получить метаданные сборочного задания
* Новый метод `DBS::setOrderKiz()` - Закрепить за сборочным заданием КиЗ
* Новый метод `DBS::setOrderUin()` - Закрепить за сборочным заданием УИН
* Новый метод `DBS::setOrderIMEI()` - Закрепить за сборочным заданием IMEI
* Новый метод `DBS::setOrderGTIN()` - Закрепить за сборочным заданием GTIN
* Удален метод `Marketplace::getOrdersClient()` - Информация по клиенту

### 4.25.1 - 23/11/2024
* Исправление в адресе метода `Tariffs::commission()`

### 4.25.0 - 20/11/2024
* Новый метод `Feedbacks::updateAnswer()` - Отредактировать ответ на отзыв
* Удален метод `Feedbacks::changeViewed()` - Просмотреть отзыв

### 4.24.0 - 19/11/2024
* Уделен устаревший метод `Questions::productRating()` - Часто спрашиваемые товары
* Уделен устаревший метод `Feedbacks::productRating()` - Средняя оценка товара
* Уделен устаревший метод `Feedbacks::parentSubjects()` - Родительские категории товаров
* Уделен устаревший метод `Feedbacks::subjectRatingTop()` - Товары с наибольшей и наименьшей средней оценкой по родительской категории
* Уделен устаревший метод `Feedbacks::subjectRating()` - Средняя оценка товаров по родительской категории

### 4.23.0 - 24/10/2024
* Доставка курьером WB (WBGO) `Marketplace()->WBGO()`

### 4.22.0 - 16/10/2024
* Новый параметр `comment` в методе `Returns::action()` для отказа в возврате с комментарием
* Уделен устаревший метод `Adv::Auction()->setAdvertSubjectActive()` - Изменение активности предметной группы для Аукциона
* Уделен устаревший метод `Adv::Auto()->setAdvertActives()` - Управление зонами показов в автоматической кампании
* Уделен устаревший метод `Adv::Auction()->advertStatistic()` - Статистика кампаний Аукцион

### 4.21.1 - 12/09/2024
* Исправление ошибки

### 4.21.0 - 09/09/2024
* Новый метод: `APIToken::getPayload()`
* Переименование метода `Calendar::promotionDetails()` в `Calendar::promotionsDetails()`

### 4.20.0 - 08/09/2024
* __Декодирование токена__ `APIToken()`

### 4.19.0 - 07/09/2024
* Новый метод API Аналитика: __Отчёт по возвратам товаров__ `Analytics::goodsReturn()`
* Новое API __Календарь акций__: `API::Calendar()`

### 4.18.0
* Новое API __Общее__: `Common()`
* Перенос методов API Контента __Новости__ в API Общее `Common()->News()`
* Новые методы API Аналитики: __Скрытые товары__ `Analytics::BannedProducts()`

### 4.17.0
* Issue #8

### 4.16.1
* Issue #7

### 4.16.0
* Новые методы API Аналитика: Отчёт по остаткам на складах `Analytics::WarehouseRemains()`

### 4.15.0
* Удален метод получения статистики Автоматических кампаний `Auto::advertStatistic()`

### 4.14.0
* Проверка подключения к WB API `ping()`

### 4.13.0
* Новый url для API Контента - https://content-api.wildberries.ru
* Удаление устаревшего имени метода `API::Recommendations()`
* Удаление автозамены имени опции "recommendations" на "recommends"

### 4.12.0
* Новое имя для методов кампании Аукцион `Adv::Auction()` (Пока как копия `Adv::SearchCatalog()`)
* Новый метод `Adv::advertStatisticByKeywords()` - Статистика по ключевым фразам
* Новый метод `Adv::Auto()->advertStatisticByKeywords()` - Статистика по ключевым фразам для Автоматических кампаний
* Новый метод `Adv::Auction()->advertStatisticByKeywords()` - Статистика по ключевым фразам для кампаний Аукцион

### 4.11.2
* Изменение README.md

### 4.11.1
* Изменение максимального значения параметра `onPage` в методах `Feedbacks::list()` и  `Feedbacks::archive()`

### 4.11.0
* Переименование метода `Adv::mns()` в `Adv::nms()`
* Переименование метода `SearchCatalog::searchAndCatalogAdvertStatistic()` в `SearchCatalog::advertStatistic()`
* Переименование метода `SearchCatalog::searchAdvertStatisticByWords()` в `SearchCatalog::advertStatisticByWords()`
* Документирование

### 4.10.4
* Новый метод WBSeller для API Рекомендаций `API::Recommends()` дублирующий `API::Recommendations()`
* Замена параметра опций `recommendations` на `recommends` с поддержкой совместимости
* Документирование

### 4.10.3
* Новый метод в API Отзывы: Возврат товара по ID отзыва `Feedbacks::orderReturn()`
* Документирование

### 4.10.2
* Документирование

### 4.10.1
* Значение locale по умолчанию `ru`
* Можно передать locale в массиве опций при создании API::class или установить через окружение в `WBSELLER_LOCALE`
* Получить текущее значение locale - `API::getLocale()`
* Изменить locale - `API::setLocale()`

### 4.10.0
* Новые методы API Аналитики: Доля бренда в продажах `Analytics::Brands()`

### 4.9.0
* Новые методы API Продвижение: Медиакампании `Adv::Media()`
* Новые методы в `Adv::Auto()`: `advertStatistic()`, `advertStatisticByWords()`
* Новые методы в `Adv::SearchCatalog()`: `searchAdvertStatisticByWords()`, `searchAndCatalogAdvertStatistic()`
* Новый метод `Adv::statistic()` - Статистика кампаний
* Новый метод `Adv::subjects()` - Предметы для кампаний
* Новый метод `Adv::nms()` - Номенклатуры для кампаний
* Перенос метода `Adv::count()` в `Adv::Media()->count()`
* Перенос метода `Adv::advert()` в `Adv::Media()->getAdvert()`
* Удален метод `Adv::allCpm()`
* Удален метод `Adv::nmActive()`
* Удален метод `Adv::paramMenu()`
* Удален метод `Adv::paramSet()`
* Удален метод `Adv::paramSubject()`
* Удален метод `Adv::setIntervals()`

### 4.8.0
* Новые методы API Продвижение: Автоматические кампании `Adv::Auto()`
* Новые методы API Продвижение: Кампании в поиске и поиск + каталог `Adv::SearchCatalog()`
* Новые методы API Продвижение: Финансы `Adv::Finances()`
* Новый метод `Adv::advertsList()` - Списки кампаний
* Новый метод `Adv::delete()` - Удаление кампании
* Удаление `Adv::Promotion()` и перенос методов `advertsInfo()` и `advertsInfoByIds()` обратно в Adv()
* Перенос метода `Adv::setActive()` в `Adv::Auto()->setAdvertSubjectActive()`
* Перенос метода `Adv::balance()` в `Adv::Finances()->balance()`
* Новый параметр `$instrument` в методу `Adv::updateCpm()`
* Удален метод `Adv::cpm()`
* Удален метод `Adv::dailyBudget()`

### 4.7.2
* Отчет по удержаниям за самовыкупы `Analytics::antifraudDetails()`
* Отчет по удержаниям за подмены товара `Analytics::incorrectAttachments()`
* Коэффициент логистики и хранения `Analytics::storageCoefficient()`
* Отчет о штрафах за отсутствие маркировки `Analytics::goodsLabeling()`
* Отчет об удержаниях за смену характеристик товара `Analytics::characteristicsChange()`
* Отчет о продажах по регионам `Analytics::regionSale()`

### 4.7.1
* Доставка силами продавца (DBS) `Marketplace()->DBS()`

### 4.7.0
* Новое API "Документы": `Documents()`

### 4.6.1
* Новый метод `Prices::quarantine()` - Получить товары в карантине

### 4.6.0
* Новое API "Чат с покупателями": `Chat()`
* Новый метод `Tariffs::commission()` - Комиссия по категориям товаров
* Выделение методов используемых при кроссбордере `Marketplace()->CrossBoard()`

### 4.5.1
* Новые методы API Аналитики: Платное хранение `Analitics()->PaidStorage()`
* Новый метод `Statistics::acceptanceReport()` - Платная приемка

### 4.5.0
* Новое API "Возвраты покупателям": `Returs()`
* Выделение методов работы с продвижением в подкласс `Adv()->Promotion()`
* Новые статусы и типы РК

### 4.4.4
* Исправление в параметрах метода `Content::updateMedia`

### 4.4.3
* Исправление ошибки

### 4.4.2
* Отчет о продажах по реализации V5

### 4.4.1
* Новый метод `Content::updateCards()`
* Изменение лимитов в методах `Content::getCardsList()` и `Content()->Trash::list()`

### 4.4.0
* Новый параметр в опциях -`apiurls` (для переназначения стандартных адресов API)
* Новый метод `API::setApiUrl()`
* Удаление методов `API::set*BaseUrl()`

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