<?php

declare(strict_types=1);

namespace Dakword\WBSeller\API\Endpoint;

use Dakword\WBSeller\API\AbstractEndpoint;
use DateTime;
use InvalidArgumentException;

class Statistics extends AbstractEndpoint
{

    /**
     * Поставки
     * 
     * Будет выгружена информация обо всех поставках у которых дата и время обновления информации в сервисе
     * больше или равно переданному значению параметра dateFrom.
     * 
     * @param DateTime $dateFrom Дата и время обновления информации в сервисе
     * 
     * @return array [ {object}, ... ]
     */
    public function incomes(DateTime $dateFrom): array
    {
        return $this->request('/api/v1/supplier/incomes', [
            'dateFrom' => $dateFrom->format(DATE_RFC3339),
        ]);
    }

    /**
     * Остатки на складах
     * 
     * Будет выгружена информация обо всех остатках товаров на складах у которых дата и время обновления информации
     * в сервисе больше или равно переданному значению параметра dateFrom.
     * Данные обновляются раз в сутки. Сервис статистики не хранит историю остатков товаров,
     * поэтому получить данные об остатках товаров на прошедшую, не сегодняшнюю, дату - невозможно.
     * 
     * @param DateTime $dateFrom Дата и время обновления информации в сервисе
     * 
     * @return array [ {object}, ... ]
     */
    public function stocks(DateTime $dateFrom): array
    {
        return $this->request('/api/v1/supplier/stocks', [
            'dateFrom' => $dateFrom->format(DATE_RFC3339),
        ]);
    }

    /**
     * Заказы за период
     * 
     * Будет выгружена информация о всех заказах у которых дата и время обновления информации в сервисе
     * больше или равно переданному значению параметра dateFrom
     * 
     * @param DateTime $dateFrom Дата и время обновления информации в сервисе
     * 
     * @return array [ {object}, ... ]
     */
    public function ordersFromDate(DateTime $dateFrom): array
    {
        return $this->request('/api/v1/supplier/orders', [
            'dateFrom' => $dateFrom->format(DATE_RFC3339),
            'flag' => 0,
        ]);
    }

    /**
     * Заказы за дату
     * 
     * Будет выгружена информация о всех заказах сделанных в дату переданную в параметре dateFrom 
     * 
     * @param DateTime $dateFrom Дата оформления заказа (время в дате значения не имеет)
     * 
     * @return array [ {object}, ... ]
     */
    public function ordersOnDate(DateTime $dateFrom): array
    {
        return $this->request('/api/v1/supplier/orders', [
            'dateFrom' => $dateFrom->format('Y-m-d'),
            'flag' => 1,
        ]);
    }

    /**
     * Продажи за период
     * 
     * Будет выгружена информация о всех продажах у которых дата и время обновления информации в сервисе
     * больше или равно переданному значению параметра dateFrom
     * 
     * @param DateTime $dateFrom Дата и время обновления информации в сервисе
     * 
     * @return array [ {object}, ... ]
     */
    public function salesFromDate(DateTime $dateFrom): array
    {
        return $this->request('/api/v1/supplier/sales', [
            'dateFrom' => $dateFrom->format(DATE_RFC3339),
            'flag' => 0,
        ]);
    }

    /**
     * Продажи за период
     * 
     * Будет выгружена информация о всех продажах у которых дата и время обновления информации в сервисе
     * больше или равно переданному значению параметра dateFrom
     * 
     * @param DateTime $dateFrom Дата и время обновления информации в сервисе
     * 
     * @return array [ {object}, ... ]
     */
    public function salesOnDate(DateTime $dateFrom): array
    {
        return $this->request('/api/v1/supplier/sales', [
            'dateFrom' => $dateFrom->format('Y-m-d'),
            'flag' => 1,
        ]);
    }

    /**
     * Отчет о продажах по реализации
     * 
     * В отчете доступны данные за последние 3 месяца.
     * 
     * @param DateTime $dateFrom Начальная дата и время отчета
     * @param DateTime $dateTo   Конечная дата и время отчета
     * @param int      $limit    Максимальное количество строк отчета, возвращаемых методом. Не может быть более 100 000
     * @param int      $rrdId    Уникальный идентификатор строки отчета. Необходим для получения отчета частями.
     *                           Загрузку отчета нужно начинать с rrdid = 0 и при последующих вызовах API передавать в запросе
     *                           значение rrd_id из последней строки, полученной в результате предыдущего вызова.
     *                           Для загрузки одного отчета может понадобиться вызывать API до тех пор,
     *                           пока количество возвращаемых строк не станет равным нулю.
     * 
     * @return array [ {object}, ... ]
     * 
     * @throws InvalidArgumentException
     */
    public function detailReport(DateTime $dateFrom, DateTime $dateTo, int $limit, int $rrdId = 0): array
    {
        $maxLimit = 100_000;
        if ($limit > $maxLimit) {
            throw new InvalidArgumentException("Превышение максимального количества запрашиваемых строк отчета: {$maxLimit}");
        }
        return ($this->request('/api/v1/supplier/reportDetailByPeriod', [
            'dateFrom' => $dateFrom->format(DATE_RFC3339),
            'dateTo' => $dateTo->format(DATE_RFC3339),
            'limit' => $limit,
            'rrdid' => $rrdId,
        ]) ?? []);
    }

    /**
     * Отчет по КиЗам
     * 
     * @param DateTime $dateFrom Начальная дата и время отчета
     * 
     * @return array [ {object}, ... ]
     */
    public function exciseGoods(DateTime $dateFrom): array
    {
        return $this->request('/api/v1/supplier/excise-goods', [
            'dateFrom' => $dateFrom->format(DATE_RFC3339),
        ]);
    }

}
