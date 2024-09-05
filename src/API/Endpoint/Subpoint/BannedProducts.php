<?php

declare(strict_types=1);

namespace Dakword\WBSeller\API\Endpoint\Subpoint;

use Dakword\WBSeller\API\Endpoint\Analytics;

/**
 * Скрытые товары
 */
class BannedProducts
{
    private Analytics $Analytics;

    public function __construct(Analytics $Analitics)
    {
        $this->Analytics = $Analitics;
    }

    /**
     * Заблокированные карточки
     *
     * Максимум 1 запрос в 10 секунд
     * @link https://openapi.wildberries.ru/analytics/api/ru/#tag/Skrytye-tovary/paths/~1api~1v1~1analytics~1banned-products~1blocked/get
     *
     * @param string $sort      Сортировка: brand - по бренду
     *                                      nmId - по артикулу WB
     *                                      title - по наименованию товара
     *                                      vendorCode - по артикулу продавца
     *                                      reason - по причине блокировки
     * @param string $direction Направление сортировки (asc - по возрастанию, desc - по убыванию)
     *
     * @return array
     *
     * @throws InvalidArgumentException Неизвестный критерий сортировки
     * @throws InvalidArgumentException Неизвестный порядок сортировки
     */
    public function blocked(string $sort = 'nmId', string $direction = 'asc'): array
    {
        if (!in_array($sort, ['brand', 'nmId', 'title', 'vendorCode', 'reason'])) {
            throw new InvalidArgumentException('Неизвестный критерий сортировки: ' . $sort);
        }
        if (!in_array($direction, ["asc", "desc"])) {
            throw new InvalidArgumentException('Неизвестный порядок сортировки: ' . $direction);
        }

        return $this->Analytics
            ->getRequest('/api/v1/analytics/banned-products/blocked', [
                'sort' => $sort,
                'order' => $direction,
            ])
        ->report ?? [];
    }

    /**
     * Скрытые из каталога
     *
     * Максимум 1 запрос в 10 секунд
     * @link https://openapi.wildberries.ru/analytics/api/ru/#tag/Skrytye-tovary/paths/~1api~1v1~1analytics~1banned-products~1shadowed/get
     *
     * @param string $sort      Сортировка: brand - по бренду
     *                                      nmId - по артикулу WB
     *                                      title - по наименованию товара
     *                                      vendorCode - по артикулу продавца
     *                                      nmRating - по рейтингу товара
     * @param string $direction Направление сортировки (asc - по возрастанию, desc - по убыванию)
     *
     * @return array
     *
     * @throws InvalidArgumentException Неизвестный критерий сортировки
     * @throws InvalidArgumentException Неизвестный порядок сортировки
     */
    public function shadowed(string $sort = 'nmId', string $direction = 'asc'): array
    {
        if (!in_array($sort, ['brand', 'nmId', 'title', 'vendorCode', 'nmRating'])) {
            throw new InvalidArgumentException('Неизвестный критерий сортировки: ' . $sort);
        }
        if (!in_array($direction, ["asc", "desc"])) {
            throw new InvalidArgumentException('Неизвестный порядок сортировки: ' . $direction);
        }

        return $this->Analytics
            ->getRequest('/api/v1/analytics/banned-products/shadowed', [
                'sort' => $sort,
                'order' => $direction,
            ])
        ->report ?? [];
    }
}
