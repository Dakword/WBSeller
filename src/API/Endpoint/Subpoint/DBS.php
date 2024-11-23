<?php
declare(strict_types=1);

namespace Dakword\WBSeller\API\Endpoint\Subpoint;

use Dakword\WBSeller\API\Endpoint\Marketplace;
use InvalidArgumentException;

class DBS
{
    private Marketplace $Marketplace;

    public function __construct(Marketplace $Marketplace)
    {
        $this->Marketplace = $Marketplace;
    }

    /**
     * Получить список новых сборочных заданий
     *
     * Возвращает список всех новых сборочных заданий у продавца на данный момент.
     * @link https://openapi.wb.ru/marketplace/api/ru/#tag/Sborochnye-zadaniya-(DBS)/paths/~1api~1v3~1dbs~1orders~1new/get
     *
     * @return object {orders: [object, ...]}
     */
    public function getNewOrders(): object
    {
        return $this->Marketplace->getRequest('/api/v3/dbs/orders/new');
    }

    /**
     * Получить информацию по завершенным сборочным заданиям
     *
     * Возвращает информацию по завершённым сборочным заданиям (проданы или отменены).
     * Можно выгрузить данные за конкретный период, максимум 30 календарных дней
     * @link https://openapi.wb.ru/marketplace/api/ru/#tag/Sborochnye-zadaniya-(DBS)/paths/~1api~1v3~1dbs~1orders/get
     *
     * @param int      $limit     Параметр пагинации. Устанавливает предельное количество возвращаемых данных. (не более 1000)
     * @param int      $next      Параметр пагинации. Устанавливает значение, с которого надо получить следующий пакет данных. Для получения полного списка данных должен быть равен 0 в первом запросе.
     * @param DateTime $dateStart С какой даты вернуть сборочные задания (заказы)
     *                            по умолчанию — дата за 30 дней до запроса
     * @param DateTime $dateEnd   По какую дату вернуть сборочные задания (заказы)
     *
     * @return object {next: int, orders: [object, ...]}
     *
     * @throws InvalidArgumentException Превышение значения параметра limit
     */
    public function getOrders(int $limit = 1_000, int $next = 0, DateTime $dateStart = null, DateTime $dateEnd = null): object
    {
        $maxLimit = 1_000;
        if ($limit > $maxLimit) {
            throw new InvalidArgumentException("Превышение максимального количества запрашиваемых строк: {$maxLimit}");
        }
        return $this->Marketplace->getRequest('/api/v3/dbs/orders',
            ['limit' => $limit, 'next' => $next]
            + ($dateStart == '' ? [] : ['dateFrom' => $dateStart->getTimestamp()])
            + ($dateEnd == '' ? [] : ['dateTo' => $dateEnd->getTimestamp()])
        );
    }

    /**
     * Получить статусы сборочных заданий
     *
     * Возвращает статусы сборочных заданий по переданному списку идентификаторов сборочных заданий.
     * supplierStatus - статус сборочного задания, триггером изменения которого является сам продавец.
     * wbStatus - статус сборочного задания в системе Wildberries.
     * @link https://openapi.wb.ru/marketplace/api/ru/#tag/Sborochnye-zadaniya-(DBS)/paths/~1api~1v3~1dbs~1orders~1status/post
     *
     * @param array $orders Список идентификаторов сборочных заданий
     *
     * @return object (orders: [{id: int, supplierStatus: string, wbStatus: string}, ...])
     * @return object В случае ошибки {code: string, message: string}
     *
     * @throws InvalidArgumentException Превышение максимального количества запрашиваемых статусов сборочных заданий
     */
    public function getOrdersStatuses(array $orders): object
    {
        $maxCount = 1_000;
        if (count($orders) > $maxCount) {
            throw new InvalidArgumentException("Превышение максимального количества запрашиваемых статусов сборочных заданий: {$maxCount}");
        }
        return $this->Marketplace->postRequest('/api/v3/dbs/orders/status', ['orders' => $orders]);
    }

    /**
     * Перевести на сборку
     *
     * Переводит сборочное задание в статус confirm ("На сборке")
     * @link https://openapi.wb.ru/marketplace/api/ru/#tag/Dostavka-silami-prodavca-(DBS)/paths/~1api~1v3~1orders~1{order}~1confirm/patch
     *
     * @param int $order_id Идентификатор сборочного задания
     *
     * @return bool
     */
    public function confirm(int $order_id): bool
    {
        $this->Marketplace->patchRequest("/api/v3/dbs/orders/{$order_id}/confirm");
        return $this->Marketplace->responseCode() == 204;
    }

    /**
     * Перевести в доставку
     *
     * Переводит сборочное задание в статус deliver ("В доставке")
     * @link https://openapi.wb.ru/marketplace/api/ru/#tag/Dostavka-silami-prodavca-(DBS)/paths/~1api~1v3~1orders~1{order}~1deliver/patch
     *
     * @param int $order_id Идентификатор сборочного задания
     *
     * @return bool
     */
    public function deliver(int $order_id): bool
    {
        $this->Marketplace->patchRequest("/api/v3/dbs/orders/{$order_id}/deliver");
        return $this->Marketplace->responseCode() == 204;
    }

    /**
     * Отменить сборочное задание
     *
     * Переводит сборочное задание в статус cancel ("Отменено продавцом").
     * @link https://openapi.wb.ru/marketplace/api/ru/#tag/Sborochnye-zadaniya-(DBS)/paths/~1api~1v3~1dbs~1orders~1{orderId}~1cancel/patch
     *
     * @param int $orderId Идентификатор сборочного задания
     *
     * @return object В случае ошибки {code: string, message: string}
     */
    public function cancelOrder(int $orderId)
    {
        return $this->Marketplace->patchRequest('/api/v3/dbs/orders/' . $orderId . '/cancel');
    }

    /**
     * Сообщить, что сборочное задание принято клиентом
     *
     * Переводит сборочное задание в статус receive ("Получено клиентом")
     * @link https://openapi.wb.ru/marketplace/api/ru/#tag/Dostavka-silami-prodavca-(DBS)/paths/~1api~1v3~1orders~1{order}~1receive/patch
     *
     * @param int $order_id Идентификатор сборочного задания
     *
     * @return bool
     */
    public function receive(int $order_id): bool
    {
        $this->Marketplace->patchRequest("/api/v3/dbs/orders/{$order_id}/receive");
        return $this->Marketplace->responseCode() == 204;
    }

    /**
     * Сообщить, что клиент отказался от сборочного задания
     *
     * Перевести в статус reject ("Отказ при получении")
     * @link https://openapi.wb.ru/marketplace/api/ru/#tag/Dostavka-silami-prodavca-(DBS)/paths/~1api~1v3~1orders~1{order}~1reject/patch
     *
     * @param int $order_id Идентификатор сборочного задания
     *
     * @return bool
     */
    public function reject(int $order_id): bool
    {
        $this->Marketplace->patchRequest("/api/v3/dbs/orders/{$order_id}/reject");
        return $this->Marketplace->responseCode() == 204;
    }

    /**
     * Информация по клиенту
     *
     * Метод позволяет получать информацию о клиенте по ID заказа.
     * Только для dbs (доставка силами продавца) и кроссбордера из Турции
     * @link https://openapi.wb.ru/marketplace/api/ru/#tag/Sborochnye-zadaniya-(DBS)/paths/~1api~1v3~1dbs~1orders~1client/post
     *
     * @param array $orders Список заказов
     *
     * @return array Информация по клиенту
     */
    public function getOrdersClient(array $orders)
    {
        return $this->Marketplace->postRequest('/api/v3/dbs/orders/client', [
            'orders' => $orders,
        ])
        ->orders;
    }

    /**
     * Получить метаданные сборочного задания
     *
     * Возвращает метаданные заказа (imei, uin, gtin, sgtin)
     * @link https://openapi.wb.ru/marketplace/api/ru/#tag/Metadannye-(DBS)/paths/~1api~1v3~1dbs~1orders~1{orderId}~1meta/get
     *
     * @param int $orderId Идентификатор сборочного задания
     *
     * @return object {meta: {imei: string, uin: string, gtin: string}}
     */
    public function getOrderMeta(int $orderId): object
    {
        return $this->Marketplace->getRequest('/api/v3/dbs/orders/' . $orderId . '/meta');
    }

    /**
     * Удалить метаданные сборочного задания
     *
     * @param int     $orderId Идентификатор сборочного задания
     * @param string $key      Название метаданных для удаления (imei, uin, gtin, sgtin)
     *
     * @return bool
     *
     * @throws InvalidArgumentException Неизвестное название метаданных
     */
    public function deleteOrderMeta(int $orderId, string $key): bool
    {
        if (!in_array($key, ['imei', 'uin', 'gtin', 'sgtin'])) {
            throw new InvalidArgumentException('Неизвестное название метаданных: ' . $key);
        }
        $this->Marketplace->deleteRequest('/api/v3/dbs/orders/' . $orderId . '/meta', [
            'key' => $key
        ]);
        return $this->Marketplace->responseCode() == 204;
    }

    /**
     * Закрепить за сборочным заданием КиЗ (маркировку Честного знака)
     * @link https://openapi.wb.ru/marketplace/api/ru/#tag/Metadannye-(DBS)/paths/~1api~1v3~1dbs~1orders~1{orderId}~1meta~1sgtin/put
     *
     * @param int   $orderId Идентификатор сборочного задания
     * @param array $sgtin   Массив КиЗов (У одного сборочного задания не может быть больше 24 маркировок)
     *
     * @throws InvalidArgumentException Превышение максимального количества элементов переданного массива
     */
    public function setOrderKiz(int $orderId, array $sgtin): bool
    {
        $maxCount = 24;
        if (count($sgtin) > $maxCount) {
            throw new InvalidArgumentException("Превышение максимального количества элементов переданного массива: {$maxCount}");
        }
        $this->Marketplace->putRequest('/api/v3/dbs/orders/' . $orderId . '/meta/sgtin', ['sgtins' => $sgtin]);
        return $this->Marketplace->responseCode() == 204;
    }

    /**
     * Закрепить за сборочным заданием УИН
     *
     * Обновляет УИН (уникальный идентификационный номер) сборочного задания.
     * У одного сборочного задания может быть только один УИН.
     * Добавлять маркировку можно только для заказов в статусе confirm.
     * @link https://openapi.wb.ru/marketplace/api/ru/#tag/Metadannye-(DBS)/paths/~1api~1v3~1dbs~1orders~1{orderId}~1meta~1uin/put
     *
     * @param int    $orderId Идентификатор сборочного задания
     * @param string $uin     УИН (16 символов)
     *
     * @return bool
     */
    public function setOrderUin(int $orderId, string $uin): bool
    {
        $this->Marketplace->putRequest('/api/v3/dbs/orders/' . $orderId . '/meta/uin', ['uin' => $uin]);
        return $this->Marketplace->responseCode() == 204;
    }

    /**
     * Закрепить за сборочным заданием IMEI
     *
     * Обновляет IMEI сборочного задания.
     * У одного сборочного задания может быть только один IMEI.
     * Добавлять маркировку можно только для заказов в статусе confirm.
     * @link https://openapi.wb.ru/marketplace/api/ru/#tag/Metadannye-(DBS)/paths/~1api~1v3~1dbs~1orders~1{orderId}~1meta~1imei/put
     *
     * @param int    $orderId Идентификатор сборочного задания
     * @param string $imei    IMEI (15 символов)
     *
     * @return bool
     */
    public function setOrderIMEI(int $orderId, string $imei): bool
    {
        $this->Marketplace->putRequest('/api/v3/dbs/orders/' . $orderId . '/meta/imei', ['imei' => $imei]);
        return $this->Marketplace->responseCode() == 204;
    }

    /**
     * Закрепить за сборочным заданием GTIN
     *
     * Обновляет GTIN сборочного задания.
     * У одного сборочного задания может быть только один GTIN.
     * Добавлять маркировку можно только для заказов в статусе confirm.
     * @link https://openapi.wb.ru/marketplace/api/ru/#tag/Metadannye-(DBS)/paths/~1api~1v3~1dbs~1orders~1{orderId}~1meta~1gtin/put
     *
     * @param int    $orderId Идентификатор сборочного задания
     * @param string $gtin    УИН (13 символов)
     *
     * @return bool
     */
    public function setOrderGTIN(int $orderId, string $gtin): bool
    {
        $this->Marketplace->putRequest('/api/v3/dbs/orders/' . $orderId . '/meta/gtin', ['gtin' => $gtin]);
        return $this->Marketplace->responseCode() == 204;
    }

}
