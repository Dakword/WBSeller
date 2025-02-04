<?php

declare(strict_types=1);

namespace Dakword\WBSeller\API\Endpoint\Subpoint;

use Dakword\WBSeller\API\Endpoint\Analytics;
use DateTime;

class PaidStorage
{
    private Analytics $Analitics;

    public function __construct(Analytics $Analitics)
    {
        $this->Analitics = $Analitics;
    }

    /**
     * Создать отчёт
     * 
     * Создаёт задание на генерацию отчёта. Можно получить отчёт максимум за 8 дней.
     * Максимум 1 запрос в минуту
     * 
     * @param DateTime $dateFrom Начало отчётного периода
     * @param DateTime $dateTo   Конец отчётного периода
     * 
     * @return string ID задания на генерацию
     */
    public function makeReport(DateTime $dateFrom, DateTime $dateTo): string
    {
        $result = $this->Analitics->getRequest('/api/v1/paid_storage', [
            'dateFrom' => $dateFrom->format(DATE_RFC3339),
            'dateTo' => $dateTo->format(DATE_RFC3339),
        ]);
        return $result->data->taskId;
    }
    
    /**
     * Проверить статус
     * 
     * Возвращает статус задания на генерацию.
     * Максимум 1 запрос в 5 секунд
     * 
     * @param string $task_id ID задания на генерацию
     * 
     * @return string Статус задания: new - новое, processing - обрабатывается, done -отчёт готов,
     *                                purged - отчёт удалён, canceled -отклонено
     */
    public function checkReportStatus(string $task_id): string
    {
        $result = $this->Analitics->getRequest('/api/v1/paid_storage/tasks/' . $task_id . '/status');
        return $result->data->status;
    }
    
    /**
     * Получить отчёт
     * 
     * Возвращает отчёт по ID задания.
     * Максимум 1 запрос в минуту
     * 
     * @param string $task_id ID задания на генерацию

     * @return array [object, object, ...]
     */
    public function getReport(string $task_id): array
    {
        return $this->Analitics->getRequest('/api/v1/paid_storage/tasks/' . $task_id . '/download');
    }
}
