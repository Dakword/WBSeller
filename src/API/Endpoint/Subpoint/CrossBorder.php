<?php

declare(strict_types=1);

namespace Dakword\WBSeller\API\Endpoint\Subpoint;

use Dakword\WBSeller\API\Endpoint\Marketplace;
use InvalidArgumentException;

class CrossBorder
{
    private Marketplace $Marketplace;

    public function __construct(Marketplace $Marketplace)
    {
        $this->Marketplace = $Marketplace;
    }

    /**
     * Получить список ссылок на этикетки для сборочных заданий,
     * которые требуются при кроссбордере
     * 
     * Нельзя запросить больше 100 этикеток за раз
     * Метод возвращает этикетки только для сборочных заданий,
     * находящихся в доставке (в статусе complete).
     * @see https://openapi.wb.ru/marketplace/api/ru/#tag/Sborochnye-zadaniya/paths/~1api~1v3~1files~1orders~1external-stickers/post
     * 
     * @param array $orders Массив идентификаторов сборочных заданий
     * 
     * @return array [
     *     {
     *         orderID: int,
     *         url: string,
     *         parcelID: string,
     *     }, ...
     * ]
     * 
     * @throws InvalidArgumentException Превышение максимального количества переданных сборочных заданий
     */
    public function getOrdersStickers(array $orders): array
    {
        $maxCount = 100;
        if (count($orders) > $maxCount) {
            throw new InvalidArgumentException("Превышение максимального количества переданных сборочных заданий: {$maxCount}");
        }
        return $this->Marketplace->postRequest('/api/v3/files/orders/external-stickers', [
            'orders' => $orders,
        ])
        ->stickers;
    }
    /**
     * История статусов для сборочных заданий кроссбордера
     * 
     * Возвращает историю статусов для сборочных заданий кроссбордера
     * @see https://openapi.wb.ru/marketplace/api/ru/#tag/Sborochnye-zadaniya/paths/~1api~1v3~1orders~1status~1history/post
     * 
     * @param array $orders Массив идентификаторов сборочных заданий
     * 
     * @return array Сборочные задания
     * 
     * @throws InvalidArgumentException
     */
    public function getOrdersStatusHistory(array $orders): array
    {
        $maxCount = 100;
        if (count($orders) > $maxCount) {
            throw new InvalidArgumentException("Превышение максимального количества переданных сборочных заданий: {$maxCount}");
        }
        return $this->Marketplace->postRequest('/api/v3/orders/status/history', [
            'orders' => $orders,
        ])
        ->orders;
    }

    /**
     * Заказы с информацией по клиенту
     * 
     * Метод позволяет получать информацию о клиенте по ID заказа.
     * Только для кроссбордера из Турции
     * @see https://openapi.wb.ru/marketplace/api/ru/#tag/Sborochnye-zadaniya/paths/~1api~1v3~1orders~1client/post
     * 
     * @param array $orders Список заказов
     * 
     * @return array Информация по клиенту
     */
    public function getOrdersClient(array $orders): array
    {
        return $this->Marketplace->postRequest('/api/v3/orders/client', [
            'orders' => $orders,
        ])
        ->orders;
    }

}
