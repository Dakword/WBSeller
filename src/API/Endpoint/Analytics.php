<?php

declare(strict_types=1);

namespace Dakword\WBSeller\API\Endpoint;

use Dakword\WBSeller\API\AbstractEndpoint;
use DateTime;
use InvalidArgumentException;

class Analytics extends AbstractEndpoint
{

    /**
     * Получение статистики КТ за выбранный период,
     * по nmID/предметам/брендам/тегам
     * 
     * Поля brandNames,objectIDs, tagIDs, nmIDs могут быть пустыми, тогда в ответе идут все карточки продавца.
     * При выборе нескольких полей в ответ приходят данные по карточкам, у которых есть все выбранные поля.
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
     * Получение статистики КТ по дням/неделям/месяцам по выбранным nmID
     * 
     * @param array    $nmIDs      Артикулы Wildberries (максимум 20)
     * @param DateTime $dateFrom   Начало периода
     * @param DateTime $dateTo     Конец периода
     * @param string   $agregation Тип аггрегации: day, week, month
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
        if (!in_array($agregation, ["day", "week", "month"])) {
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
     * 
     * @param DateTime $dateFrom   Начало периода
     * @param DateTime $dateTo     Конец периода
     * @param array    $filter     Фильтр по параметрам [
     *                                 'brandNames' => [string, string, ...],
     *                                 'objectIDs' => [int, int, ...],
     *                                 'tagIDs' => [int, int, ...],
     *                             ]
     * @param string   $agregation Тип аггрегации: day, week, month
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
        if (!in_array($agregation, ["day", "week", "month"])) {
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

    /**
     * Отчёт по товарам с обязательной маркировкой
     * 
     * Возвращает операции по маркируемым товарам.
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
    
    private function getFromFilter(string $param, array $filter)
    {
        $key = strtolower($param);
        $modifKeys = array_change_key_case($filter);
        return (array_key_exists($key, $modifKeys) ? $modifKeys[$key] : []);
    }
}
