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
        return $this->postRequest('/content/v1/analytics/nm-report/detail', [
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

    public function nmReportGrouped(DateTime $dateFrom, DateTime $dateTo, array $filter = [], int $page = 1, string $timezone='Europe/Moscow', string $orderBy = 'openCard', string $direction = 'desc'): object
    {
        if (!in_array($orderBy, ["openCard", "addToCart", "orders", "avgRubPrice", "ordersSumRub", "stockMpQty", "stockWbQty"])) {
            throw new InvalidArgumentException('Неизвестный вид сортировки: ' . $orderBy);
        }
        if (!in_array($direction, ["asc", "desc"])) {
            throw new InvalidArgumentException('Неизвестный порядок сортировки: ' . $direction);
        }
        return $this->postRequest('/content/v1/analytics/nm-report/grouped', [
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
    //public function exciseReport(DateTime $dateFrom, DateTime $dateTo, array $countries = [])
    //{
    //    return $this->postRequest('/api/v1/excise-report?dateFrom=' . $dateFrom->format('Y-m-d') . '&dateTo=' . $dateTo->format('Y-m-d'), [
    //        'countries' => $countries,
    //    ]);
    //}
    
    private function getFromFilter(string $param, array $filter)
    {
        $key = strtolower($param);
        $modifKeys = array_change_key_case($filter);
        return (array_key_exists($key, $modifKeys) ? $modifKeys[$key] : []);
    }
}
