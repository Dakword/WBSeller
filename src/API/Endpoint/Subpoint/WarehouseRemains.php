<?php

declare(strict_types=1);

namespace Dakword\WBSeller\API\Endpoint\Subpoint;

use Dakword\WBSeller\API\Endpoint\Analytics;

/**
 * Отчёт по остаткам на складах
 * https://openapi.wb.ru/analytics/api/ru/#tag/Otchyot-po-ostatkam-na-skladah
 */
class WarehouseRemains
{
    private Analytics $Analytics;

    public function __construct(Analytics $Analytics)
    {
        $this->Analytics = $Analytics;
    }

    /**
     * Заказать отчёт
     *
     * Создаёт задание на генерацию отчёта
     * Максимум 1 запрос в минуту
     * @link https://openapi.wb.ru/analytics/api/ru/#tag/Otchyot-po-ostatkam-na-skladah/paths/~1api~1v1~1warehouse_remains/get
     *
     * @param array     $groupBy    Разбивка: brand - по брендам
     *                                        subject - по предметам
     *                                        vendorcode - по артикулам продавца
     *                                        nmid - по по артикулам WB (в ответе будет поле volume)
     *                                        barcode - по баркодам
     *                                        size - по размерам
     * @param int|null  $withVolume Фильтр по объёму: false - без габаритов
     *                                                null - не применять фильтр
     *                                                true - свыше трёх литров
     * @param bool|null $withPhoto Фильтр по фото: false - без фото
     *                                             null - не применять фильтр
     *                                             true - с фото
     *
     * @return string ID задания на генерацию
     */
    public function makeReport(array $groupBy = [], ?int $withVolume = null, ?bool $withPhoto = null): string
    {
        return $this->Analytics->getRequest('/api/v1/warehouse_remains', [
            'locale' => $this->Analytics->locale(),
            'groupByBrand' => in_array('brand', $groupBy),
            'groupBySubject' => in_array('subject', $groupBy),
            'groupBySa' => in_array('vendorcode', $groupBy),
            'groupByNm' => in_array('nmid', $groupBy),
            'groupByBarcode' => in_array('barcode', $groupBy),
            'groupBySize' => in_array('size', $groupBy),
            'filterVolume' => is_null($withVolume) ? 0 : ($withVolume ? 3 : -1),
            'filterPics' => is_null($withPhoto) ? 0 : ($withPhoto ? 1 : -1),
        ])
        ->data->taskId;
    }

    /**
     * Проверить статус
     *
     * Возвращает статус задания на генерацию.
     * Максимум 1 запрос в 5 секунд
     * @link https://openapi.wb.ru/analytics/api/ru/#tag/Otchyot-po-ostatkam-na-skladah/paths/~1api~1v1~1warehouse_remains~1tasks~1{task_id}~1status/get
     *
     * @param string $task_id ID задания на генерацию
     *
     * @return string Статус задания: new - новое
     *                                processing - обрабатывается
     *                                done - отчёт готов
     *                                purged - отчёт удалён
     *                                canceled - отклонено
     */
    public function checkReportStatus(string $task_id): string
    {
        return $this->Analitics
            ->getRequest('/api/v1/warehouse_remains/tasks/' . $task_id . '/status')
        ->status;
    }

    /**
     * Получить отчёт
     *
     * Возвращает отчёт по ID задания.
     * Максимум 1 запрос в минуту.
     * @link https://openapi.wb.ru/analytics/api/ru/#tag/Otchyot-po-ostatkam-na-skladah/paths/~1api~1v1~1warehouse_remains~1tasks~1{task_id}~1download/get
     *
     * @param string $task_id ID задания на генерацию
     *
     * @return array
     */
    public function getReport(string $task_id): array
    {
        return $this->Analitics
            ->getRequest('/api/v1/warehouse_remains/tasks/' . $task_id . '/download');
    }
}
