<?php

declare(strict_types=1);

namespace Dakword\WBSeller\API\Endpoint;

use Dakword\WBSeller\API\AbstractEndpoint;
use Dakword\WBSeller\API\Endpoint\Subpoint\Warehouses;
use Dakword\WBSeller\API\Endpoint\Subpoint\Passes;
use DateTime;
use InvalidArgumentException;

class Marketplace extends AbstractEndpoint
{

    /**
     * Сервис для работы с пропусками.
     * 
     * @return Passes
     */
    public function Passes(): Passes
    {
        return new Passes($this);
    }

    /**
     * Сервис для работы со складами.
     * 
     * @return Warehouses
     */
    public function Warehouses(): Warehouses
    {
        return new Warehouses($this);
    }

    public function __call($method, $parameters)
    {
        if(method_exists($this, $method)) {
            return call_user_func_array([$this, $method], $parameters);
        }
        throw new InvalidArgumentException('Magic request method ' . $method . ' not exists');
    }

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
     * 
     * @param string $supplyId Идентификатор поставки
     * @param string $type     Формат штрихкода ("svg", "zplv", "zplh", "png")
     * 
     * @return object {barcode: string, file: string}
     * 
     * @throws InvalidArgumentException Неизвестный формат штрихкода
     */
    public function getSupplyBarcode(string $supplyId, string $type): object
    {
        if (!in_array($type, ['svg', 'zplv', 'zplh', 'png'])) {
            throw new InvalidArgumentException('Неизвестный формат штрихкода: ' . $type);
        }
        return $this->getRequest('/api/v3/supplies/' . $supplyId . '/barcode', ['type' => $type]);
    }

    /**
     * Отменить сборочное задание
     * 
     * Переводит сборочное задание в статус cancel ("Отменено продавцом").
     * 
     * @param int $orderId Идентификатор сборочного задания
     * 
     * @return object В случае ошибки {code: string, message: string}
     */
    public function cancelOrder(int $orderId)
    {
        return $this->patchRequest('/api/v3/orders/' . $orderId . '/cancel');
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
    public function getOrdersStatuses(array $orders): object
    {
        $maxCount = 1_000;
        if (count($orders) > $maxCount) {
            throw new InvalidArgumentException("Превышение максимального количества запрашиваемых статусов сборочных заданий: {$maxCount}");
        }
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
     * @param array $sgtin   Массив КиЗов (У одного сборочного задания не может быть больше 24 маркировок)
     * 
     */
    public function setOrderKiz(int $orderId, array $sgtin)
    {
        $maxCount = 24;
        if (count($sgtin) > $maxCount) {
            throw new InvalidArgumentException("Превышение максимального количества строк переданного массива: {$maxCount}");
        }
        return $this->postRequest('/api/v3/orders/' . $orderId . '/meta/sgtin', ['sgtin' => $sgtin]);
    }

    /**
     * Закрепить за сборочным заданием УИН
     * 
     * Обновляет УИН (уникальный идентификационный номер) сборочного задания.
     * У одного сборочного задания может быть только один УИН.
     * Добавлять маркировку можно только для заказов в статусе confirm. 
     * 
     * @param int    $orderId Идентификатор сборочного задания
     * @param string $uin     УИН (16 символов)
     * 
     * @return bool
     */
    public function setOrderUin(int $orderId, string $uin): bool 
    {
        $this->putRequest('/api/v3/orders/' . $orderId . '/meta/uin', ['uin' => $uin]);
        return $this->responseCode() == 204;
    }

    /**
     * Закрепить за сборочным заданием IMEI
     * 
     * Обновляет IMEI сборочного задания.
     * У одного сборочного задания может быть только один IMEI.
     * Добавлять маркировку можно только для заказов в статусе confirm. 
     * 
     * @param int    $orderId Идентификатор сборочного задания
     * @param string $imei    IMEI (15 символов)
     * 
     * @return bool
     */
    public function setOrderIMEI(int $orderId, string $imei): bool 
    {
        $this->putRequest('/api/v3/orders/' . $orderId . '/meta/imei', ['imei' => $imei]);
        return $this->responseCode() == 204;
    }

    /**
     * Закрепить за сборочным заданием GTIN
     * 
     * Обновляет GTIN сборочного задания.
     * У одного сборочного задания может быть только один GTIN.
     * Добавлять маркировку можно только для заказов в статусе confirm. 
     * 
     * @param int    $orderId Идентификатор сборочного задания
     * @param string $gtin    УИН (13 символов)
     * 
     * @return bool
     */
    public function setOrderGTIN(int $orderId, string $gtin): bool 
    {
        $this->putRequest('/api/v3/orders/' . $orderId . '/meta/gtin', ['gtin' => $gtin]);
        return $this->responseCode() == 204;
    }

    /**
     * Получить метаданные сборочного задания
     * 
     * Возвращает метаданные заказа (imei, uin, gtin)
     * 
     * @param int $orderId Идентификатор сборочного задания
     * 
     * @return object {meta: {imei: string, uin: string, gtin: string}}
     */
    public function getOrderMeta(int $orderId): object
    {
        return $this->getRequest('/api/v3/orders/' . $orderId . '/meta');
    }    

    /**
     * Удалить метаданные сборочного задания
     * 
     * @param int     $orderId Идентификатор сборочного задания
     * @param string $key      Название метаданных для удаления (imei, uin, gtin)
     * 
     * @return bool
     * 
     * @throws InvalidArgumentException Неизвестное название метаданных
     */
    public function deleteOrderMeta(int $orderId, string $key): bool
    {
        if (!in_array($key, ['imei', 'uin', 'gtin'])) {
            throw new InvalidArgumentException('Неизвестное название метаданных: ' . $key);
        }
        $this->deleteRequest('/api/v3/orders/' . $orderId . '/meta', [
            'key' => $key
        ]);
        return $this->responseCode() == 204;
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
     * @param string $type     Формат штрихкода ("svg", "zplv", "zplh", "png")
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
     * Получить список ссылок на этикетки для сборочных заданий,
     * которые требуются при кроссбордере
     * 
     * Возвращает список ссылок на этикетки для сборочных заданий, которые требуются при кроссбордере.
     * 
     * Метод возвращает этикетки только для сборочных заданий, находящихся на сборке (в статусе confirm).
     * 
     * @param array  $orderIds Идентификаторы сборочных заданий (не более 100)
     * 
     * @return object {stickers: [object, ...]}
     * 
     * @throws InvalidArgumentException Превышение максимального количества запрашиваемых этикеток
     */
    public function getOrdersExternalStickers(array $orderIds): object
    {
        $maxCount = 100;
        if (count($orderIds) > $maxCount) {
            throw new InvalidArgumentException("Превышение максимального количества запрашиваемых этикеток: {$maxCount}");
        }
        return $this->postRequest('/api/v3/files/orders/external-stickers', ['orders' => $orderIds]);
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

    /**
     * Получить список коробов поставки
     * 
     * @param string $supplyId Идентификатор поставки
     * 
     * @return object {trbxes: [object, ...]}
     */
    public function getSupplyBoxes(string $supplyId): object
    {
        return $this->getRequest('/api/v3/supplies/' . $supplyId . '/trbx');
    }
 
    /**
     * Добавить короба к поставке
     * 
     * Добавляет требуемое количество коробов в поставку.
     * Можно добавить, только пока поставка на сборке.
     * 
     * @param string $supplyId Идентификатор поставки
     * @param int    $amount   Количество коробов, которые необходимо добавить к поставке
     * 
     * @return object {trbxIds: [string, ...]}
     * 
     * @throws InvalidArgumentException ревышение максимального количества добавляемых коробов
     */
    public function addSupplyBoxes(string $supplyId, int $amount = 1): object
    {
        $maxCount = 1_000;
        if ($amount > $maxCount) {
            throw new InvalidArgumentException("Превышение максимального количества добавляемых коробов: {$maxCount}");
        }
        return $this->postRequest('/api/v3/supplies/' . $supplyId . '/trbx', ['amount' => $amount]);
    }

    /**
     * Удалить короба из поставки
     * 
     * Убирает заказы из перечисленных коробов поставки и удаляет короба.
     * Можно удалить, только пока поставка на сборке. 
     * 
     * @param string $supplyId Идентификатор поставки
     * @param array  $boxeIds Список ID коробов, которые необходимо удалить
     * 
     * @return bool
     */
    public function deleteSupplyBoxes(string $supplyId, array $boxeIds): bool
    {
        $this->deleteRequest('/api/v3/supplies/' . $supplyId . '/trbx', ['trbxIds' => $boxeIds]);
        return $this->responseCode() == 204;
    }
    
    /**
     * Добавить заказы к коробу
     * 
     * Добавляет заказы в короб для выбранной поставки.
     * Можно добавить, только пока поставка на сборке.
     * 
     * @param string $supplyId Идентификатор поставки
     * @param string $boxId    ID короба
     * @param array  $orderIds Список заказов, которые необходимо добавить в короб
     * 
     * @return bool
     */
    public function addBoxOrders(string $supplyId, string $boxId, array $orderIds): bool
    {
        $this->patchRequest('/api/v3/supplies/' . $supplyId . '/trbx/' . $boxId, ['orderIds' => $orderIds]);
        return $this->responseCode() == 204;
    }
    
    /**
     * Удалить заказ из короба
     * 
     * Удаляет заказ из короба выбранной поставки.
     * Можно удалить, только пока поставка на сборке.
     * 
     * @param string $supplyId Идентификатор поставки
     * @param string $boxId    ID короба
     * @param int    $orderId  ID сборочного задания
     * 
     * @return bool
     */
    public function deleteBoxOrder(string $supplyId, string $boxId, int $orderId): bool
    {
        $this->deleteRequest('/api/v3/supplies/' . $supplyId . '/trbx/' . $boxId . '/orders/' . $orderId);
        return $this->responseCode() == 204;
    }
    
    /**
     * Получить стикеры коробов поставки
     * 
     * Возвращает стикеры QR в svg, zplv (вертикальный), zplh (горизонтальный), png.
     * Можно получить, только если в коробе есть заказы.
     * Размер стикеров: 580x400 пикселей
     * 
     * @param string $supplyId Идентификатор поставки
     * @param array  $boxIds   Список ID коробов, по которым необходимо вернуть стикеры
     * @param string $type     Формат штрихкода ("svg", "zplv", "zplh", "png")
     * 
     * @return object {stickers: {}}
     * 
     * @throws InvalidArgumentException Неизвестный формат штрихкода
     */
    public function getSupplyBoxStickers(string $supplyId, array $boxIds, string $type = 'svg')
    {
        if (!in_array($type, ['svg', 'zplv', 'zplh', 'png'])) {
            throw new InvalidArgumentException('Неизвестный формат штрихкода: ' . $type);
        }
        return $this->postRequest('/api/v3/supplies/' . $supplyId . '/trbx/stickers?type=' . $type, ['trbxIds' => $boxIds]);
    }
}
