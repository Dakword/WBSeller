<?php

declare(strict_types=1);

namespace Dakword\WBSeller\API\Endpoint;

use Dakword\WBSeller\API\AbstractEndpoint;
use Dakword\WBSeller\API\Endpoint\Subpoint\BannedProducts;
use Dakword\WBSeller\API\Endpoint\Subpoint\Brands;
use Dakword\WBSeller\API\Endpoint\Subpoint\PaidStorage;
use Dakword\WBSeller\API\Endpoint\Subpoint\WarehouseRemains;
use DateTime;
use InvalidArgumentException;

class Analytics extends AbstractEndpoint
{
    /**
     * Скрытые товары
     *
     * @return BannedProducts
     */
    public function BannedProducts(): BannedProducts
    {
        return new BannedProducts($this);
    }
    /**
     * Доля бренда в продажах
     *
     * @return Brands
     */
    public function Brands(): Brands
    {
        return new Brands($this);
    }

    /**
     * Платное хранение
     *
     * @return PaidStorage
     */
    public function PaidStorage(): PaidStorage
    {
        return new PaidStorage($this);
    }

    /**
     * Отчёт по остаткам на складах
     * @link https://openapi.wb.ru/analytics/api/ru/#tag/Otchyot-po-ostatkam-na-skladah
     *
     * @return WarehouseRemains
     */
    public function WarehouseRemains(): WarehouseRemains
    {
        return new WarehouseRemains($this);
    }

    /*
     * ВОРОНКА ПРОДАЖ
     * --------------------------------------------------------------------------
     * @link https://openapi.wb.ru/analytics/api/ru/#tag/Voronka-prodazh
     */

    /**
     * Получение статистики КТ за выбранный период,
     * по nmID/предметам/брендам/тегам
     *
     * Поля brandNames,objectIDs, tagIDs, nmIDs могут быть пустыми, тогда в ответе идут все карточки продавца.
     * При выборе нескольких полей в ответ приходят данные по карточкам, у которых есть все выбранные поля.
     * Можно получить отчёт максимум за последний год (365 дней).
     * Максимум 3 запроса в минуту.
     * @link https://openapi.wb.ru/analytics/api/ru/#tag/Voronka-prodazh/paths/~1api~1v2~1nm-report~1detail/post
     *
     * @param DateTime $dateFrom  Начало периода
     * @param DateTime $dateTo    Конец периода
     * @param array    $filter    Фильтр по параметрам [
     *                                'brandNames' => [string, string, ...],
     *                                'objectIDs' => [int, int, ...],
     *                                'tagIDs' => [int, int, ...],
     *                                'nmIDs' => [int, int, ...],
     *                            ]
     * @param int      $page      Ноомер страницы
     * @param string   $timezone  Временная зона. Если не указано, то по умолчанию используется Europe/Moscow.
     * @param string   $orderBy   Вид сортировки: openCard - по открытию карточки (переход на страницу товара)
     *                                            addToCart - по добавлениям в корзину
     *                                            orders - по кол-ву заказов
     *                                            avgRubPrice - по средней цене в рублях
     *                                            ordersSumRub - по сумме заказов в рублях
     *                                            stockMpQty - по кол-ву остатков маркетплейса шт.
     *                                            stockWbQty - по кол-ву остатков на складе шт.
     * @param string   $direction Направление сортировки (asc - по возрастанию, desc - по убыванию)
     *
     * @return object {
     *      data: {
     *          page: integer, isNextPage: bool,
     *          cards: [objectj, object, ...]
     *      },
     *      error: bool, errorText: string, additionalErrors: [object, object, ...]
     * }
     *
     * @throws InvalidArgumentException Неизвестный вид сортировки
     * @throws InvalidArgumentException Неизвестный порядок сортировки
     */
    public function nmReportDetail(DateTime $dateFrom, DateTime $dateTo, array $filter = [], int $page = 1, string $timezone='Europe/Moscow', string $orderBy = 'openCard', string $direction = 'desc'): object
    {
        if (!in_array($orderBy, ["openCard", "addToCart", "orders", "avgRubPrice", "ordersSumRub", "stockMpQty", "stockWbQty", "cancelSumRub", "cancelCount", "buyoutCount", "buyoutSumRub"])) {
            throw new InvalidArgumentException('Неизвестный вид сортировки: ' . $orderBy);
        }
        if (!in_array($direction, ["asc", "desc"])) {
            throw new InvalidArgumentException('Неизвестный порядок сортировки: ' . $direction);
        }
        return $this->postRequest('/api/v2/nm-report/detail', [
            'nmIDs' => $this->getFromFilter('nmIDs', $filter),
            'brandNames' => $this->getFromFilter('brandNames', $filter),
            'objectIDs' => $this->getFromFilter('objectIDs', $filter),
            'tagIDs' => $this->getFromFilter('tagIDs', $filter),
            'period' => [
                'begin' => $dateFrom->format('Y-m-d H:i:s'),
                'end' => $dateTo->format('Y-m-d H:i:s'),
            ],
            'timezone' => $timezone,
            'page' => $page,
            'orderBy' => [
                'field' => $orderBy,
                'mode' => $direction,
            ],
        ]);
    }

    /**
     * Получение статистики КТ за период,
     * сгруппированный по предметам, брендам и тегам
     *
     * Поля brandNames, objectIDs, tagIDs могут быть пустыми,
     * тогда группировка происходит по всем карточкам продавца.
     * Можно получить отчёт максимум за последний год (365 дней).
     * Максимум 3 запроса в минуту.
     * @link https://openapi.wb.ru/analytics/api/ru/#tag/Voronka-prodazh/paths/~1api~1v2~1nm-report~1grouped/post
     *
     * @param DateTime $dateFrom  Начало периода
     * @param DateTime $dateTo    Конец периода
     * @param array    $filter    Фильтр по параметрам [
     *                                'brandNames' => [string, string, ...],
     *                                'objectIDs' => [int, int, ...],
     *                                'tagIDs' => [int, int, ...],
     *                            ]
     * @param int      $page      Ноомер страницы
     * @param string   $timezone  Временная зона. Если не указано, то по умолчанию используется Europe/Moscow.
     * @param string   $orderBy   Вид сортировки: openCard - по открытию карточки (переход на страницу товара)
     *                                            addToCart - по добавлениям в корзину
     *                                            orders - по кол-ву заказов
     *                                            avgRubPrice - по средней цене в рублях
     *                                            ordersSumRub - по сумме заказов в рублях
     *                                            stockMpQty - по кол-ву остатков маркетплейса шт.
     *                                            stockWbQty - по кол-ву остатков на складе шт.
     * @param string   $direction Направление сортировки (asc - по возрастанию, desc - по убыванию)
     *
     * @return object {
     *      data: {
     *          page: integer, isNextPage: bool,
     *          groups: [objectj, object, ...]
     *      },
     *      error: bool, errorText: string, additionalErrors: [object, object, ...]
     * }
     *
     * @throws InvalidArgumentException Неизвестный вид сортировки
     * @throws InvalidArgumentException Неизвестный порядок сортировки
     */
    public function nmReportGrouped(DateTime $dateFrom, DateTime $dateTo, array $filter = [], int $page = 1, string $timezone = 'Europe/Moscow', string $orderBy = 'openCard', string $direction = 'desc'): object
    {
        if (!in_array($orderBy, ["openCard", "addToCart", "orders", "avgRubPrice", "ordersSumRub", "stockMpQty", "stockWbQty"])) {
            throw new InvalidArgumentException('Неизвестный вид сортировки: ' . $orderBy);
        }
        if (!in_array($direction, ["asc", "desc"])) {
            throw new InvalidArgumentException('Неизвестный порядок сортировки: ' . $direction);
        }
        return $this->postRequest('/api/v2/nm-report/grouped', [
            'brandNames' => $this->getFromFilter('brandNames', $filter),
            'objectIDs' => $this->getFromFilter('objectIDs', $filter),
            'tagIDs' => $this->getFromFilter('tagIDs', $filter),
            'period' => [
                'begin' => $dateFrom->format('Y-m-d H:i:s'),
                'end' => $dateTo->format('Y-m-d H:i:s'),
            ],
            'timezone' => $timezone,
            'page' => $page,
            'orderBy' => [
                'field' => $orderBy,
                'mode' => $direction,
            ],
        ]);
    }

    /**
     * Получение статистики КТ по дням/неделям по выбранным nmID
     *
     * Можно получить отчёт максимум за последнюю неделю.
     * Максимум 3 запроса в минуту.
     * @link https://openapi.wb.ru/analytics/api/ru/#tag/Voronka-prodazh/paths/~1api~1v2~1nm-report~1detail~1history/post
     *
     * @param array    $nmIDs      Артикулы Wildberries (максимум 20)
     * @param DateTime $dateFrom   Начало периода
     * @param DateTime $dateTo     Конец периода
     * @param string   $agregation Тип аггрегации: day, week
     * @param string   $timezone   Временная зона
     *
     * @return object {
     *      data: [object, object, ...],
     *      error: bool, errorText: string, additionalErrors: [object, object, ...]
     * }
     *
     * @throws InvalidArgumentException Превышение максимального количества переданных артикулов
     * @throws InvalidArgumentException Неизвестный тип агрегации
     */
    public function nmReportDetailHistory(array $nmIDs, DateTime $dateFrom, DateTime $dateTo, string $agregation = 'day', string $timezone = 'Europe/Moscow'): object
    {
        $maxCount = 20;
        if (count($nmIDs) > $maxCount) {
            throw new InvalidArgumentException("Превышение максимального количества переданных артикулов: {$maxCount}");
        }
        if (!in_array($agregation, ["day", "week"])) {
            throw new InvalidArgumentException('Неизвестный тип агрегации: ' . $agregation);
        }
        return $this->postRequest('/api/v2/nm-report/detail/history', [
            'nmIDs' => $nmIDs,
            'period' => [
                'begin' => $dateFrom->format('Y-m-d'),
                'end' => $dateTo->format('Y-m-d'),
            ],
            'timezone' => $timezone,
            'aggregationLevel' => $agregation,
        ]);
    }

    /**
     * Получение статистики КТ по дням за период,
     * сгруппированный по предметам, брендам и тегам
     *
     * Параметры фильтра brandNames, objectIDs, tagIDs могут быть пустыми,
     * тогда группировка происходит по всем карточкам продавца.
     * В запросе произведение количества предметов, брендов, тегов не должно быть больше 16.
     * Можно получить отчёт максимум за последнюю неделю.
     * Максимум 3 запроса в минуту.
     * @link https://openapi.wb.ru/analytics/api/ru/#tag/Voronka-prodazh/paths/~1api~1v2~1nm-report~1grouped~1history/post
     *
     * @param DateTime $dateFrom   Начало периода
     * @param DateTime $dateTo     Конец периода
     * @param array    $filter     Фильтр по параметрам [
     *                                 'brandNames' => [string, string, ...],
     *                                 'objectIDs' => [int, int, ...],
     *                                 'tagIDs' => [int, int, ...],
     *                             ]
     * @param string   $agregation Тип аггрегации: day, week
     * @param string   $timezone   Временная зона
     *
     * @return object {
     *      data: [object, object, ...],
     *      error: bool, errorText: string, additionalErrors: [object, object, ...]
     * }
     *
     * @throws InvalidArgumentException Превышение максимального произведения количества предметов, брендов, тегов
     * @throws InvalidArgumentException Неизвестный тип агрегации
     */
    public function nmReportGroupedHistory(DateTime $dateFrom, DateTime $dateTo, array $filter = [], string $agregation = 'day', string $timezone = 'Europe/Moscow'): object
    {
        $max = 16;
        if (
            count($this->getFromFilter('objectIDs', $filter))
          * count($this->getFromFilter('brandNames', $filter))
          * count($this->getFromFilter('tagIDs', $filter)) > $max
        ) {
            throw new InvalidArgumentException("Превышение максимального произведения количества предметов, брендов, тегов: {$max}");
        }
        if (!in_array($agregation, ["day", "week"])) {
            throw new InvalidArgumentException('Неизвестный тип агрегации: ' . $agregation);
        }
        return $this->postRequest('/api/v2/nm-report/grouped/history', [
            'brandNames' => $this->getFromFilter('brandNames', $filter),
            'objectIDs' => $this->getFromFilter('objectIDs', $filter),
            'tagIDs' => $this->getFromFilter('tagIDs', $filter),
            'period' => [
                'begin' => $dateFrom->format('Y-m-d'),
                'end' => $dateTo->format('Y-m-d'),
            ],
            'timezone' => $timezone,
            'aggregationLevel' => $agregation,
        ]);
    }

    /*
     * ТОВАРЫ С ОБЯЗАТЕЛЬНОЙ МАРКИРОВКОЙ
     * --------------------------------------------------------------------------
     * @link https://openapi.wb.ru/analytics/api/ru/#tag/Tovary-s-obyazatelnoj-markirovkoj
     */

    /**
     * Отчёт по товарам с обязательной маркировкой
     *
     * Возвращает операции по маркируемым товарам.
     * Максимум 10 запросов за 5 часов.
     * @link https://openapi.wb.ru/analytics/api/ru/#tag/Tovary-s-obyazatelnoj-markirovkoj/paths/~1api~1v1~1analytics~1excise-report/post
     *
     * @param DateTime $dateFrom  Дата начала отчётного периода
     * @param DateTime $dateTo    Дата окончания отчётного периода
     * @param array    $countries Код стран по стандарту ISO 3166-2.
     *                            "AM", "BY", "KG", "KZ", "RU", "UZ"
     *                            Чтобы получить данные по всем странам, оставьте параметр пустым
     *
     * @return array [object, object, ...]
     */
    public function exciseReport(DateTime $dateFrom, DateTime $dateTo, array $countries = [])
    {
        return $this->postRequest('/api/v1/analytics/excise-report?dateFrom=' . $dateFrom->format('Y-m-d') . '&dateTo=' . $dateTo->format('Y-m-d'), [
            'countries' => $countries,
        ]);
    }

    /*
     * ПЛАТНАЯ ПРИЕМКА
     * --------------------------------------------------------------------------
     * @link https://openapi.wb.ru/analytics/api/ru/#tag/Platnaya-priyomka
     */

    /**
     * Отчет о платной приемке
     *
     * Возвращает даты и стоимость приёмки. Можно получить отчёт максимум за 31 день.
     * Максимум 1 запрос в минуту
     * @link https://openapi.wb.ru/analytics/api/ru/#tag/Platnaya-priyomka/paths/~1api~1v1~1analytics~1acceptance-report/get
     *
     * @param DateTime $dateFrom Начало отчётного периода
     * @param DateTime $dateTo   Конец отчётного периода
     *
     * @return array Отчет [object, object, ...]
     */
    public function acceptanceReport(DateTime $dateFrom, DateTime $dateTo):array
    {
        $result = $this->getRequest('/api/v1/analytics/acceptance-report', [
            'dateFrom' => $dateFrom->format('Y-m-d'),
            'dateTo' => $dateTo->format('Y-m-d'),
        ]);
        return $result->report;
    }

     /*
     * ОТЧЕТЫ ПО УДЕРЖАНИЯМ
     * --------------------------------------------------------------------------
     * @link https://openapi.wb.ru/analytics/api/ru/#tag/Otchyoty-po-uderzhaniyam
     */

    /**
     * Отчет по удержаниям за самовыкупы
     *
     * Отчёт формируется каждую неделю по средам, до 7:00 по московскому времени, и содержит данные за одну неделю.
     * Также можно получить отчёт за всё время с августа 2023, для этого не передавайте параметр $date.
     * Максимум 10 запросов за 100 минут.
     * @link https://openapi.wb.ru/analytics/api/ru/#tag/Otchyoty-po-uderzhaniyam/paths/~1api~1v1~1analytics~1antifraud-details/get
     *
     * @param DateTime|null $date Дата, которая входит в отчётный период
     *
     * @return array
     */
    public function antifraudDetails(?DateTime $date = null): array
    {
        $result = $this->getRequest('/api/v1/analytics/antifraud-details', $date ? ['date' => $date->format('Y-m-d')] : []);
        return $result->datails;
    }

    /**
     * Отчет об удержаниях за подмену товара
     *
     * Возвращает отчёт об удержаниях за отправку не тех товаров, пустых коробок или коробок без товара,
     * но с посторонними предметами. В таких случаях удерживается 100% от стоимости заказа.
     * Можно получить отчёт максимум за 31 день, доступны данные с июня 2023.
     * Максимум 1 запрос в минуту.
     * @link https://openapi.wb.ru/analytics/api/ru/#tag/Otchyoty-po-uderzhaniyam/paths/~1api~1v1~1analytics~1incorrect-attachments/get
     *
     * @param DateTime $dateFrom Начало отчётного периода
     * @param DateTime $dateTo   Конец отчётного периода
     *
     * @return array
     */
    public function incorrectAttachments(DateTime $dateFrom, DateTime $dateTo): array
    {
        $result = $this->getRequest('/api/v1/analytics/incorrect-attachments', [
            'dateFrom' => $dateFrom->format('Y-m-d'),
            'dateTo' => $dateTo->format('Y-m-d'),
        ]);
        return $result->report;
    }

    /**
     * Коэффициент логистики и хранения
     *
     * Возвращает коэффициенты логистики и хранения.
     * Они рассчитываются на неделю (с понедельника по воскресенье).
     * Можно получить данные с 31.10.2022.
     * Максимум 1 запрос в минуту.
     * @link https://openapi.wb.ru/analytics/api/ru/#tag/Otchyoty-po-uderzhaniyam/paths/~1api~1v1~1analytics~1storage-coefficient/get
     *
     * @param DateTime|null $date Дата, которая входит в отчётный период
     *
     * @return array
     */
    public function storageCoefficient(?DateTime $date = null): array
    {
        $result = $this->getRequest('/api/v1/analytics/storage-coefficient', $date ? ['date' => $date->format('Y-m-d')] : []);
        return $result->report;
    }

    /**
     * Отчёт о штрафах за отсутствие обязательной маркировки товаров
     *
     * В отчёте представлены фотографии товаров, на которых маркировка отсутствует
     * либо не считывается.
     * Можно получить данные максимум за 31 день, начиная с марта 2024.
     * Максимум 10 запросов за 10 минут
     * @link https://openapi.wb.ru/analytics/api/ru/#tag/Otchyoty-po-uderzhaniyam/paths/~1api~1v1~1analytics~1goods-labeling/get
     *
     * @param DateTime $dateFrom Начало отчётного периода
     * @param DateTime $dateTo   Конец отчётного периода
     *
     * @return array
     */
    public function goodsLabeling(DateTime $dateFrom, DateTime $dateTo): array
    {
        $result = $this->getRequest('/api/v1/analytics/goods-labeling', [
            'dateFrom' => $dateFrom->format('Y-m-d'),
            'dateTo' => $dateTo->format('Y-m-d'),
        ]);
        return $result->report;
    }

    /**
     * Отчёт об удержаниях за смену характеристик товара
     *
     * Если товары после приёмки не соответствуют заявленным цветам и размерам,
     * и на складе их перемаркировали с правильными характеристиками,
     * по таким товарам назначается штраф.
     * Можно получить отчёт максимум за 31 день, доступны данные с 28 декабря 2021.
     * Максимум 10 запросов за 10 минут
     * @link https://openapi.wb.ru/analytics/api/ru/#tag/Otchyoty-po-uderzhaniyam/paths/~1api~1v1~1analytics~1characteristics-change/get
     *
     * @param DateTime $dateFrom Начало отчётного периода
     * @param DateTime $dateTo   Конец отчётного периода
     *
     * @return array
     */
    public function characteristicsChange(DateTime $dateFrom, DateTime $dateTo): array
    {
        $result = $this->getRequest('/api/v1/analytics/characteristics-change', [
            'dateFrom' => $dateFrom->format('Y-m-d'),
            'dateTo' => $dateTo->format('Y-m-d'),
        ]);
        return $result->report;
    }

    /*
     * ПРОДАЖИ ПО РЕГИОНАМ
     * --------------------------------------------------------------------------
     * @link https://openapi.wb.ru/analytics/api/ru/#tag/Prodazhi-po-regionam
     */

    /**
     * Отчет о продажах сгруппированный по регионам стран
     *
     * Возвращает данные продаж, сгруппированные по регионам стран.
     * Можно получить отчёт максимум за 31 день.
     * Максимум 1 запрос в 10 секунд
     * @link https://openapi.wb.ru/analytics/api/ru/#tag/Prodazhi-po-regionam/paths/~1api~1v1~1analytics~1region-sale/get
     *
     * @param DateTime $dateFrom Начало отчётного периода
     * @param DateTime $dateTo   Конец отчётного периода
     *
     * @return array
     */
    public function regionSale(DateTime $dateFrom, DateTime $dateTo): array
    {
        $result = $this->getRequest('/api/v1/analytics/region-sale', [
            'dateFrom' => $dateFrom->format('Y-m-d'),
            'dateTo' => $dateTo->format('Y-m-d'),
        ]);
        return $result->report;
    }

    private function getFromFilter(string $param, array $filter)
    {
        $key = strtolower($param);
        $modifKeys = array_change_key_case($filter);
        return (array_key_exists($key, $modifKeys) ? $modifKeys[$key] : []);
    }

    /*
     * ОТЧЕТ ПО ВОЗВРАТАМ ТОВАРОВ
     * --------------------------------------------------------------------------
     * @link https://openapi.wb.ru/analytics/api/ru/#tag/Otchyot-po-vozvratam-tovarov
     */

    /**
     * Получить отчёт по возвратам товаров
     *
     * Возвращает перечень возвратов товаров продавцу.
     * Одним запросом можно получить отчёт максимум за 31 день.
     * Максимум 1 запрос в минуту
     * @link https://openapi.wb.ru/analytics/api/ru/#tag/Otchyot-po-vozvratam-tovarov/paths/~1api~1v1~1analytics~1goods-return/get
     *
     * @param DateTime $dateFrom Начало отчётного периода
     * @param DateTime $dateTo   Конец отчётного периода
     *
     * @return array
     */
    public function goodsReturn(DateTime $dateFrom, DateTime $dateTo): array
    {
        $result = $this->getRequest('/api/v1/analytics/goods-return', [
            'dateFrom' => $dateFrom->format('Y-m-d'),
            'dateTo' => $dateTo->format('Y-m-d'),
        ]);
        return $result->report ?? [];
    }

    /*
     * Динамика оборачиваемости
     * --------------------------------------------------------------------------
     */

    /**
     * Ежедневная динамика
     *
     * Метод предоставляет данные о ежедневной динамике.
     * Можно получить отчёт максимум за 31 день.
     * Максимум 1 запрос в 10 секунд.
     * @link https://dev.wildberries.ru/ru/openapi/reports/#tag/Dinamika-oborachivaemosti/paths/~1api~1v1~1turnover-dynamics~1daily-dynamics/get
     *
     * @param DateTime $dateFrom Дата начала отчётного периода
     * @param DateTime $dateTo   Дата окончания отчётного периода
     */
    public function dailyDynamics(DateTime $dateFrom, DateTime $dateTo): array
    {
        $result = $this->getRequest('/api/v1/turnover-dynamics/daily-dynamics', [
            'dateFrom' => $dateFrom->format('Y-m-d'),
            'dateTo' => $dateTo->format('Y-m-d'),
        ]);
        return $result->report ?? [];
    }

}
