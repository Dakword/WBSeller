<?php

declare(strict_types=1);

namespace Dakword\WBSeller\API\Endpoint;

use Dakword\WBSeller\API\AbstractEndpoint;
use DateTime;
use InvalidArgumentException;

class Marketplace extends AbstractEndpoint
{

    /**
     * Список поставок
     * 
     * @param int $limit Параметр пагинации. Устанавливает предельное количество возвращаемых данных.
     * @param int $next  Параметр пагинации. Устанавливает значение, с которого надо получить следующий пакет данных.
     *                   Для получения полного списка данных должен быть равен 0 в первом запросе.
     * 
     * @return object {next: int, supplies: [ object, ...]}
     * 
     * @throws InvalidArgumentException Превышение максимального количества запрашиваемых данных
     */
    public function getSuppliesList(int $limit = 1_000, int $next = 0): object
    {
        $maxLimit = 1_000;
        if ($limit > $maxLimit) {
            throw new InvalidArgumentException("Превышение максимального количества запрашиваемых данных: {$maxLimit}");
        }
        return $this->getRequest('/api/v3/supplies', ['limit' => $limit, 'next' => $next]);
    }

    /**
     * Создание новой поставки
     * 
     * @param int $name Наименование поставки
     * 
     * @return object Объект с идентификатором поставки {id: string}
     * 
     * @throws InvalidArgumentException Превышение максимальной длинны наименования поставки
     */
    public function createSupply(string $name = ''): object
    {
        $maxLength = 128;
        if (mb_strlen($name) > $maxLength) {
            throw new InvalidArgumentException("Превышение максимальной длинны наименования поставки: {$maxLength}");
        }
        return $this->postRequest('/api/v3/supplies', ['name' => $name]);
    }

    /**
     * Получить информацию о поставке
     * 
     * @param string $supplyId Идентификатор поставки
     * 
     * @return object
     */
    public function getSupply(string $supplyId): object
    {
        return $this->getRequest('/api/v3/supplies/' . $supplyId);
    }

    /**
     * Удалить поставку
     * 
     * Удаляет поставку, если она активна и за ней не закреплено ни одно сборочное задание
     * 
     * @param string $supplyId Идентификатор поставки
     */
    public function deleteSupply(string $supplyId)
    {
        return $this->deleteRequest('/api/v3/supplies/' . $supplyId);
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
        return $this->getRequest('/api/v3/supplies/' . $supplyId . '/orders');
    }

    /**
     * Добавить к поставке сборочное задание
     * 
     * Добавляет к поставке заказы и переводит их в статус confirm ("В сборке")
     * Также может перемещать сборочное задание между активными поставками, либо из закрытой в активную при условии,
     * что сборочное задание требует повторной отгрузки.
     * Добавить в поставку возможно только задания с соответствующим сКГТ-признаком (isLargeCargo),
     * либо если поставке ещё не присвоен сКГТ-признак (isLargeCargo = null).
     * 
     * @param string $supplyId Идентификатор поставки
     * @param int    $orderId  Идентификатор сборочного задания
     * 
     * @return object В случае ошибки {code: string, message: string}
     */
    public function addSupplyOrder(string $supplyId, int $orderId)
    {
        return $this->patchRequest('/api/v3/supplies/' . $supplyId . '/orders/' . $orderId);
    }

    /**
     * Передать поставку в доставку (Закрытие поставки)
     * 
     * Закрывает поставку и переводит все сборочные задания в ней в статус complete ("В доставке").
     * После закрытия поставки новые сборочные задания к ней добавить будет невозможно.
     * Передать поставку в доставку можно только при наличии в ней хотя бы одного сборочного задания.
     * 
     * @param string $supplyId Идентификатор поставки
     * 
     * @return object В случае ошибки {code: string, message: string}
     */
    public function closeSupply(string $supplyId)
    {
        return $this->patchRequest('/api/v3/supplies/' . $supplyId . '/deliver');
    }

    /**
     * Получить все сборочные задания на повторную отгрузку
     * 
     * Возвращает все сборочные задания, требующие повторной отгрузки.
     * 
     * @return object (orders: [object, ...])
     * @return object В случае ошибки {code: string, message: string}
     */
    public function getReShipmentOrdersSupplies(): object
    {
        return $this->getRequest('/api/v3/supplies/orders/reshipment');
    }

    /**
     * QR поставки в заданном формате
     * 
     * Возвращает QR в svg, zplv (вертикальный), zplh (горизонтальный), png.
     * Можно получить, только если поставка передана в доставку.
     * Доступные размеры: 580х400 и 400х300 пикселей.
     * 
     * @param string $supplyId Идентификатор поставки
     * @param string $type     Формат штрихкода ("pdf", "svg", "zplv", "zplh", "png")
     * @param string $size     Размер этикетки ("40x30", "58x40")
     * 
     * @return object {barcode: string, file: string}
     * 
     * @throws InvalidArgumentException Неизвестный формат штрихкода
     * @throws InvalidArgumentException Неизвестный размер этикетки
     */
    public function getSupplyBarcode(string $supplyId, string $type, string $size): object
    {
        if (!in_array($type, ['svg', 'zplv', 'zplh', 'png'])) {
            throw new InvalidArgumentException('Неизвестный формат штрихкода: ' . $type);
        }
        if (!in_array($size, ['40x30', '58x40'])) {
            throw new InvalidArgumentException('Неизвестный размер этикетки: ' . $type);
        }
        return $this->getRequest('/api/v3/supplies/' . $supplyId . '/barcode', ['type' => $type, 'width' => explode('x', $size)[0], 'height' => explode('x', $size)[1]]);
    }

    /**
     * Отменить сборочное задание
     * 
     * Переводит сборочное задание в статус cancel ("Отменено продавцом").
     * 
     * @param string $orderId Идентификатор сборочного задания
     * 
     * @return object В случае ошибки {code: string, message: string}
     */
    public function cancelOrder(string $orderId)
    {
        return $this->patchRequest('/api/v3/orders/' . $orderId . '/cancel');
    }

    /**
     * Подтвердить сборочное задание
     * 
     * Переводит сборочное задание в статус confirm ("На сборке").
     * 
     * @param string $orderId Идентификатор сборочного задания
     * 
     * @return object В случае ошибки {code: string, message: string}
     */
    public function confirmOrder(string $orderId)
    {
        return $this->patchRequest('/api/v3/orders/' . $orderId . '/confirm');
    }

    /**
     * Передать сборочное задание в доставку
     * 
     * Переводит сборочное задание в статус deliver ("В доставке").
     * 
     * @param string $orderId Идентификатор сборочного задания
     * 
     * @return object В случае ошибки {code: string, message: string}
     */
    public function deliverOrder(string $orderId)
    {
        return $this->patchRequest('/api/v3/orders/' . $orderId . '/deliver');
    }

    /**
     * Сборочное задание выдано
     * 
     * Переводит сборочное задание в статус receive ("Получено клиентом").
     * 
     * @param string $orderId Идентификатор сборочного задания
     * 
     * @return object В случае ошибки {code: string, message: string}
     */
    public function receiveOrder(string $orderId)
    {
        return $this->patchRequest('/api/v3/orders/' . $orderId . '/receive');
    }

    /**
     * Отказ от получения сборочного задания
     * 
     * Переводит сборочное задание в статус reject ("Отказ при получении").
     * 
     * @param string $orderId Идентификатор сборочного задания
     * 
     * @return object В случае ошибки {code: string, message: string}
     */
    public function rejectOrder(string $orderId)
    {
        return $this->patchRequest('/api/v3/orders/' . $orderId . '/reject');
    }

    /**
     * Получить статусы сборочных заданий
     * 
     * Возвращает статусы сборочных заданий по переданному списку идентификаторов сборочных заданий.
     * supplierStatus - статус сборочного задания, триггером изменения которого является сам продавец.
     * wbStatus - статус сборочного задания в системе Wildberries.
     * 
     * @param array $orders Список идентификаторов сборочных заданий
     * 
     * @return object (orders: [{id: int, supplierStatus: string, wbStatus: string}, ...])
     * @return object В случае ошибки {code: string, message: string}
     */
    public function gerOrdersStatuses(array $orders): object
    {
        return $this->postRequest('/api/v3/orders/status', ['orders' => $orders]);
    }

    /**
     * Получить информацию по сборочным заданиям
     * 
     * Возвращает информацию по сборочным заданиям без их актуального статуса.
     * Данные по сборочному заданию, возвращающиеся в данном методе, не меняются.
     * Рекомендуется использовать для получения исторических данных.
     * 
     * @param int      $limit     Параметр пагинации. Устанавливает предельное количество возвращаемых данных. (не более 1000)
     * @param int      $next      Параметр пагинации. Устанавливает значение, с которого надо получить следующий пакет данных. Для получения полного списка данных должен быть равен 0 в первом запросе.
     * @param DateTime $dateStart С какой даты вернуть сборочные задания (заказы)
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
        return $this->getRequest('/api/v3/orders',
            ['limit' => $limit, 'next' => $next]
            + ($dateStart == '' ? [] : ['dateFrom' => $dateStart->getTimestamp()])
            + ($dateEnd == '' ? [] : ['dateTo' => $dateEnd->getTimestamp()])
        );
    }

    /**
     * Получить список новых сборочных заданий
     * 
     * Возвращает список всех новых сборочных заданий у продавца на данный момент.
     * 
     * @return object {orders: [object, ...]}
     */
    public function getNewOrders(): object
    {
        return $this->getRequest('/api/v3/orders/new');
    }

    /**
     * Закрепить за сборочным заданием КиЗ (маркировку Честного знака)
     * 
     * @param int   $orderId Идентификатор сборочного задания
     * @param array $sgtin   Массив КиЗов (У одного сборочного заказа не может быть больше 10 маркировок)
     * 
     */
    public function setOrderKiz(int $orderId, array $sgtin)
    {
        $maxCount = 10;
        if (count($sgtin) > $maxCount) {
            throw new InvalidArgumentException("Превышение максимального количества строк переданного массива: {$maxCount}");
        }
        return $this->postRequest('/api/v3/orders/' . $orderId . '/meta/sgtin', ['sgtin' => $sgtin]);
    }

    /**
     * Получить этикетки для сборочных заданий
     * 
     * Возвращает список этикеток по переданному массиву сборочных заданий.
     * Можно запросить этикетку в формате svg, zplv (вертикальный), zplh (горизонтальный), png.
     * Метод возвращает этикетки только для сборочных заданий, находящихся на сборке (в статусе confirm)
     * Доступные размеры: 580х400 и 400х300 пикселей.
     * 
     * @param array  $orderIds Идентификаторы сборочных заданий (не более 100)
     * @param string $type     Формат штрихкода ("pdf", "svg", "zplv", "zplh", "png")
     * @param string $size     Размер этикетки ("40x30", "58x40")
     * 
     * @return object {stickers: [object, ...]}
     * 
     * @throws InvalidArgumentException Неизвестный формат штрихкода
     * @throws InvalidArgumentException Неизвестный размер этикетки
     * @throws InvalidArgumentException Превышение максимального количества запрашиваемых этикеток
     */
    public function getOrdersStickers(array $orderIds, string $type, string $size): object
    {
        $maxCount = 100;
        if (count($orderIds) > $maxCount) {
            throw new InvalidArgumentException("Превышение максимального количества запрашиваемых этикеток: {$maxCount}");
        }
        if (!in_array($type, ['svg', 'zplv', 'zplh', 'png'])) {
            throw new InvalidArgumentException('Неизвестный формат штрихкода: ' . $type);
        }
        if (!in_array($size, ['40x30', '58x40'])) {
            throw new InvalidArgumentException('Неизвестный размер этикетки: ' . $type);
        }
        return $this->postRequest(
            '/api/v3/orders/stickers?type=' . $type . '&width=' . explode('x', $size)[0] . '&height=' . explode('x', $size)[1],
            ['orders' => $orderIds]);
    }

    /**
     * Cписок складов продавца
     * 
     * @return array [{id: int, name: string}, ...]
     */
    public function getWarehouses(): array
    {
        return $this->getRequest('/api/v2/warehouses');
    }

    /**
     * Обновление остатков товаров по складу
     * 
     * @param int   $warehouseId Идентификатор склада продавца
     * @param array $stocks      Массив баркодов товаров и их остатков (не более 1000)
     * 
     * @throws InvalidArgumentException Превышение максимального количества обновляемых остатков
     */
    public function updateWarehouseStocks(int $warehouseId, array $stocks)
    {
        $maxCount = 1_000;
        if (count($stocks) > $maxCount) {
            throw new InvalidArgumentException("Превышение максимального количества обновляемых остатков: {$maxCount}");
        }
        return $this->putRequest('/api/v3/stocks/' . $warehouseId, ['stocks' => $stocks]);
    }

    /**
     * Удаление остатков товаров по складу
     * 
     * Действие необратимо. Удаленный остаток будет необходимо загрузить повторно для возобновления продаж.
     * 
     * @param int   $warehouseId Идентификатор склада продавца
     * @param array $skus        Массив баркодов (не более 1000)
     * 
     * @throws InvalidArgumentException Превышение максимального количества удаляемых остатков
     */
    public function deleteWarehouseStocks(int $warehouseId, array $skus)
    {
        $maxCount = 1_000;
        if (count($skus) > $maxCount) {
            throw new InvalidArgumentException("Превышение максимального количества удаляемых остатков: {$maxCount}");
        }
        return $this->deleteRequest('/api/v3/stocks/' . $warehouseId, ['skus' => $skus]);
    }

    /**
     * Получить остатки товаров по складу
     * 
     * @param int   $warehouseId Идентификатор склада продавца
     * @param array $skus        Массив баркодов (не более 1000)
     * 
     * @return object {stocks: [object, ...]}
     * 
     * @throws InvalidArgumentException Превышение максимального количества запрашиваемых остатков
     */
    public function getWarehouseStocks(int $warehouseId, array $skus)
    {
        $maxCount = 1_000;
        if (count($skus) > $maxCount) {
            throw new InvalidArgumentException("Превышение максимального количества запрашиваемых остатков: {$maxCount}");
        }
        return $this->postRequest('/api/v3/stocks/' . $warehouseId, ['skus' => $skus]);
    }

}
