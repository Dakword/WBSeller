<?php

declare(strict_types=1);

namespace Dakword\WBSeller\API\Endpoint;

use Dakword\WBSeller\API\AbstractEndpoint;
use Dakword\WBSeller\Enum\OrderStatus;
use Dakword\WBSeller\Enum\SupplyStatus;
use DateTime;
use InvalidArgumentException;

class Marketplace extends AbstractEndpoint
{

    /**
     * Список поставок
     * 
     * @param string $status ACTIVE - активные поставки
     *                       ON_DELIVERY - поставки в пути (которые ещё не приняты на складе).
     * 
     * @return object {supplies: [ {supplyId: string}, ...]}
     * 
     * @throws InvalidArgumentException Неизвестный статус поставки
     */
    public function getSuppliesList(string $status): object
    {
        if (!in_array($status, [SupplyStatus::ACTIVE, SupplyStatus::ON_DELIVERY])) {
            throw new InvalidArgumentException('Неизвестный статус поставки: ' . $status);
        }
        return $this->request('/api/v2/supplies', 'GET', ['status' => $status]);
    }

    /**
     * Создание новой поставки
     * 
     * @return object Объект с идентификатором поставки {supplyId: string}
     * @return object У поставщика уже есть активная поставка {data: any, error: bool, errorText: string, additionalErrors: string}
     */
    public function createSupply(): object
    {
        return $this->request('/api/v2/supplies', 'POST');
    }

    /**
     * Добавление к поставке заказов
     * 
     * Добавляет к поставке заказы и переводит их в статус 1 ("В сборке")
     * 
     * @param string $supplyId Идентификатор поставки
     * @param array  $orders   Список заказов
     * 
     * @return object В случае ошибки {data: any, error: bool, errorText: string, additionalErrors: string}
     */
    public function addSupplyOrder(string $supplyId, array $orders)
    {
        return $this->request('/api/v2/supplies/' . $supplyId, 'PUT', ['orders' => $orders]);
    }

    /**
     * Закрытие поставки
     * 
     * Закрывает поставку.
     * Для закрытия поставки требуется хотя бы один закреплённый за ней заказ.
     * После закрытия к поставке нельзя будет добавить новые заказы.
     * 
     * @param string $supplyId Идентификатор поставки
     * 
     * @return object В случае ошибки {data: any, error: bool, errorText: string, additionalErrors: string}
     */
    public function closeSupply(string $supplyId)
    {
        return $this->request('/api/v2/supplies/' . $supplyId . '/close', 'POST');
    }

    /**
     * Штрихкод поставки в заданном формате
     * 
     * Возвращает штрихкод поставки в заданном формате: pdf или svg.
     * Штрихкод генерируется в формате code-128.
     * Массив байтов передаётся закодированным в base64.
     * 
     * @param string $supplyId Идентификатор поставки
     * @param string $type     Формат штрихкода ("pdf", "svg")
     * 
     * @return object {mimeType: string ("application/pdf", "image/svg+xml"), name: string, file: base64}
     * 
     * @throws InvalidArgumentException Неизвестный формат штрихкода
     */
    public function getSupplyBarcode(string $supplyId, string $type): object
    {
        if (!in_array($type, ['svg', 'pdf'])) {
            throw new InvalidArgumentException('Неизвестный формат штрихкода: ' . $type);
        }
        return $this->request('/api/v2/supplies/' . $supplyId . '/barcode', 'GET', ['type' => $type]);
    }

    /**
     * Штрихкод поставки в формате PDF
     * 
     * Возвращает штрихкод поставки в формате pdf.
     * Штрихкод генерируется в формате code-128.
     * Массив байтов передаётся закодированным в base64.
     * 
     * @param string $supplyId Идентификатор поставки
     * 
     * @return object {mimeType: string ("application/pdf"), name: string, file: base64}
     */
    public function getSupplyPdfBarcode(string $supplyId): object
    {
        return $this->getSupplyBarcode($supplyId, 'pdf');
    }

    /**
     * Штрихкод поставки в формате SVG
     * 
     * Возвращает штрихкод поставки в формате svg.
     * Штрихкод генерируется в формате code-128.
     * Массив байтов передаётся закодированным в base64.
     * 
     * @param string $supplyId Идентификатор поставки
     * 
     * @return object {mimeType: string ("image/svg+xml"), name: string, file: base64}
     */
    public function getSupplySvgBarcode(string $supplyId): object
    {
        return $this->getSupplyBarcode($supplyId, 'svg');
    }

    /**
     * Список заказов, закреплённых за поставкой
     * 
     * @param string $supplyId Идентификатор поставки
     * 
     * @return object {orders: [object, ...]}
     */
    public function getSupplyOrders(string $supplyId): object
    {
        return $this->request('/api/v2/supplies/' . $supplyId . '/orders', 'GET');
    }

    /**
     * Список товаров с остатками
     * 
     * Возвращает список товаров поставщика с их остатками
     * 
     * @param int    $page   Номер страницы
     * @param int    $limit  Количество записей на странице
     * @param string $search Поиск по всем полям таблицы
     * 
     * @return object {total: int, stocks: [object, ...]}
     */
    public function getStockList(int $page, int $limit, string $search = ''): object
    {
        return $this->request('/api/v2/stocks', 'GET', [
                'skip' => ($page - 1) * $limit,
                'take' => $limit,
                'search' => $search,
        ]);
    }

    /**
     * Обновление остатков товара
     * За раз можно загрузить 1000 строк
     * 
     * @param array $data [{barcode: string, warehouseId: int, stock: int}, ...]
     * 
     * @return object {data: any, error: bool, errorText: string, additionalErrors: string}
     *                Если error = true, то во время обновления произошла ошибка.
     *                В ответе будут перечислены баркоды остатков, которые не загрузились.
     *                {data: {error: [{barcode: string, err: string}, ...]}, ...}
     */
    public function updateStock(array $data): object
    {
        $maxLimit = 1_000;
        if (count($data) > $maxLimit) {
            throw new InvalidArgumentException("Превышение максимального количества обновляемых остатков: {$maxLimit}");
        }
        return $this->request('/api/v2/stocks', 'POST', $data);
    }

    /**
     * Удаление остатков товара
     * 
     * @param array $data [{barcode: string, warehouseId: int}, ...]
     * 
     * @return object {data: any, error: bool, errorText: string, additionalErrors: string}
     *                Если error = true, то среди остатков в теле запроса есть неверно указанные.
     *                В ответе будут неверно указанные остатки с сообщением о том, что указано неверно.
     *                {data: {error: [{barcode: string, warehouseId: int, err: string}, ...]}, ...}
     */
    public function deleteStock(array $data): object
    {
        return $this->request('/api/v2/stocks', 'DELETE', $data);
    }

    /**
     * Cписок складов поставщика
     * 
     * @return array [{id: int, name: string}, ...]
     */
    public function getWarehouses(): array
    {
        return $this->request('/api/v2/warehouses', 'GET');
    }

    /**
     * Список сборочных заданий
     * 
     * Метод возвращает список сборочных заданий поставщика.
     * 
     * @param int      $page      Номер страницы
     * @param int      $limit     Количество записей на странице (не более чем 1000)
     * @param DateTime $dateStart С какой даты вернуть сборочные задания (заказы)
     * @param int      $status    Статус заказа (-1 для любого)
     * @param DateTime $dateEnd   По какую дату вернуть сборочные задания (заказы)
     * @param int      $id        Идентификатор сборочного задания, если нужно получить данные по определенному заказу
     * 
     * @return object {total: int, orders: [object, ...]}
     * 
     * @throws InvalidArgumentException Превышение значения параметра limit
     */
    public function getOrders(int $page, int $limit, DateTime $dateStart, int $status = -1, DateTime $dateEnd = null, int $id = 0): object
    {
        $maxLimit = 1_000;
        if ($limit > $maxLimit) {
            throw new InvalidArgumentException("Превышение максимального количества запрашиваемых строк: {$maxLimit}");
        }
        if ($status !== -1 && !in_array($status, OrderStatus::allowedStatuses())) {
            throw new InvalidArgumentException("Неизвестный статус заказа: {$status}");
        }
        return $this->request('/api/v2/orders', 'GET', [
                'skip' => ($page - 1) * $limit,
                'take' => $limit,
                'date_start' => $dateStart->format(DATE_RFC3339),
                ] + ($dateEnd == '' ? [] : [
                'date_end' => $dateEnd->format(DATE_RFC3339),
                ]) + ($status == -1 ? [] : [
                'status' => $status,
                ]) + ($id == 0 ? [] : [
                'id' => $id,
        ]));
    }

    /**
     * Метод возвращает сборочное заданий поставщика по его номеру
     * 
     * @param int $id Идентификатор сборочного задания
     * 
     * @return object {total: int, orders: [object, ...]}
     */
    public function getOrder(int $id): object
    {
        return $this->request('/api/v2/orders', 'GET', [
                'date_start' => (new DateTime('2000-01-01 00:00:00'))->format(DATE_RFC3339),
                'id' => $id,
                'skip' => 0,
                'take' => 100,
        ]);
    }

    /**
     * Обновление статуса сборочных заданий
     * 
     * @param array $data [{orderId: string, status: int, sgtin: [object, ...]}]
     * 
     * @return object {total: int, stocks: [object, ...]}
     */
    public function updateOrderStatus(array $data): object
    {
        return $this->request('/api/v2/orders', 'PUT', $data);
    }

    /**
     * Cписок этикеток сборочных заданий
     * 
     * Возвращает список QR этикеток по переданному массиву сборочных заданий
     * 
     * @param array $orderIds Идентификаторы сборочных заданий (не более 1000)
     * 
     * @return object {
     * 		data: [ {orderId: int, sticker: {}}, ... ],
     * 		error: bool, errorText: string, additionalErrors: string
     * }
     */
    public function getOrdersStickers(array $orderIds): object
    {
        return $this->request('/api/v2/orders/stickers', 'POST', ['orderIds' => $orderIds]);
    }

    /**
     * Cписок QR стикеров в формате pdf
     * 
     * Возвращает список QR этикеток в формате pdf по переданному массиву сборочных заданий
     * 
     * @param array $orderIds Идентификаторы сборочных заданий (не более 1000)
     * 
     * @return object {
     * 		data: {file: base64, name: string, mimeType: string},
     * 		error: bool, errorText: string, additionalErrors: string
     * }
     */
    public function getOrdersPdfStickers(array $orderIds): object
    {
        return $this->request('/api/v2/orders/stickers/pdf', 'POST', ['orderIds' => $orderIds]);
    }

}
