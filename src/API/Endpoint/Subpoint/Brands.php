<?php

declare(strict_types=1);

namespace Dakword\WBSeller\API\Endpoint\Subpoint;

use Dakword\WBSeller\API\Endpoint\Analytics;
use DateTime;

class Brands
{
    private Analytics $Analytics;

    public function __construct(Analytics $Analitics)
    {
        $this->Analytics = $Analitics;
    }

    /**
     * Бренды продавца
     *
     * Возвращает список брендов продавца.
     * Можно получить только бренды, которые: продавались за последние 90 дней
     *                                        есть на складе WB
     * Максимум 1 запрос в минуту
     * @link https://openapi.wb.ru/analytics/api/ru/#tag/Dolya-brenda-v-prodazhah/paths/~1api~1v1~1analytics~1brand-share~1brands/get
     *
     * @return array
     */
    public function getBrands(): array
    {
        $result = $this->Analytics->getRequest('/api/v1/analytics/brand-share/brands');
        return $result->data;
    }

    /**
     * Родительские категории бренда
     *
     * Можно получить данные с 1 ноября 2022 года, максимум за 365 дней.
     * Максимум 1 запрос в 5 секунд
     * @link https://openapi.wb.ru/analytics/api/ru/#tag/Dolya-brenda-v-prodazhah/paths/~1api~1v1~1analytics~1brand-share~1parent-subjects/get
     *
     * @param string   $brandName Бренд
     * @param DateTime $dateFrom  Начало отчётного периода
     * @param DateTime $dateTo    Конец отчётного периода
     *
     * @return array
     */
    public function getBrandParentSubjects(string $brandName, DateTime $dateFrom, DateTime $dateTo): array
    {
        $result = $this->Analytics->getRequest('/api/v1/analytics/brand-share/parent-subjects', [
            'brand' => $brandName,
            'dateFrom' => $dateFrom->format('Y-m-d'),
            'dateTo' => $dateTo->format('Y-m-d'),
            'locale' => $this->Analytics->locale(),
        ]);
        return $result->data;
    }

    /**
     * Отчёт по доле бренда продавца в продажах
     *
     * Можно получить данные с 1 ноября 2022 года, максимум за 365 дней.
     * Максимум 1 запрос в 5 секунд
     * @link https://openapi.wb.ru/analytics/api/ru/#tag/Dolya-brenda-v-prodazhah/paths/~1api~1v1~1analytics~1brand-share/get
     *
     * @param string   $brandName Бренд
     * @param int      $parentId  ID родительской категории
     * @param DateTime $dateFrom  Начало отчётного периода
     * @param DateTime $dateTo    Конец отчётного периода
     *
     * @return array
     */
    public function getReport(string $brandName, int $parentId, DateTime $dateFrom, DateTime $dateTo): array
    {
        $result = $this->Analytics->getRequest('/api/v1/analytics/brand-share', [
            'brand' => $brandName,
            'parentId' => $parentId,
            'dateFrom' => $dateFrom->format('Y-m-d'),
            'dateTo' => $dateTo->format('Y-m-d'),
        ]);
        return $result->data;
    }
}
