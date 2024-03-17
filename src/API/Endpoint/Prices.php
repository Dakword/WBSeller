<?php

declare(strict_types=1);

namespace Dakword\WBSeller\API\Endpoint;

use Dakword\WBSeller\API\AbstractEndpoint;
use InvalidArgumentException;

class Prices extends AbstractEndpoint
{

    /**
     * Получение информации по ценам и скидкам.
     * 
     * @param int $page   Номер страницы
     * @param int $onPage Количество результатов на странице
     * 
     * @return object {
     *      data: {listGoods: [{nmId: int, vendorCode: string, sizes: array, currencyIsoCode4217: string, discount: int, editableSizePrice: bool}, ...]},
     *      error: bool, errorText: string
     * }
     * 
     * @throws InvalidArgumentException Превышение максимального размера страницы
     */
    public function getPrices(int $page = 1, int $onPage = 1_000): object
    {
        $maxLimit = 1_000;
        if ($onPage > $maxLimit) {
            throw new InvalidArgumentException("Превышение максимального размера страницы: {$maxLimit}");
        }
        return $this->getRequest('/api/v2/list/goods/filter', [
            'offset' => --$page * $onPage,
            'limit' => $onPage,
        ]);
    }

    /**
     * Получение информации по ценам и скидкам для артикула WB.
     * 
     * @param int $nmID Идентификатор номенклатуры
     * 
     * @return object {
     *      data: {listGoods: [{nmId: int, vendorCode: string, sizes: array, currencyIsoCode4217: string, discount: int, editableSizePrice: bool}, ...]},
     *      error: bool, errorText: string
     * }
     */
    public function getNmIdPrice(int $nmID): object
    {
        return $this->getRequest('/api/v2/list/goods/filter', [
            'offset' => 0,
            'limit' => 1_000,
            'filterNmID' => $nmID,
        ]);
    }

    /**
     * Получение информации по ценам и скидкам для размеров артикула WB.
     * 
     * Работает только для товаров из категорий, где можно устанавливать цены отдельно для разных размеров.
     * (Для таких товаров в ответе метода getPrices у артикулов editableSizePrice: true)
     * 
     * @param int $nmID   Идентификатор номенклатуры
     * @param int $page   Номер страницы
     * @param int $onPage Количество результатов на странице
     * 
     * @return object {
     *      data: {listGoods: [{nmId: int, sizeID: int, vendorCode: string, price: int, currencyIsoCode4217: string, discountedPrice:int, discount: int, techSizeName: int, editableSizePrice: bool}, ...]},
     *      error: bool, errorText: string
     * }
     */
    public function getNmIdSizesPrices(int $nmID, int $page = 1, int $onPage = 1_000): object
    {
        return $this->getRequest('/api/v2/list/goods/size/nm', [
            'offset' => --$page * $onPage,
            'limit' => $onPage,
            'nmID' => $nmID,
        ]);
    }

    /**
     * Установить цены и скидки
     * 
     * За раз можно загрузить не более 1000 номенклатур.
     * Цена и скидка не могут быть пустыми одновременно.
     * 
     * @param array $prices Товары, цены и скидки для них.
     *                      [{nmID: int, price: int, discount: int}, ...]
     * 
     * @return object {
     *      data: {id: int, alreadyExists: bool}
     *      error: bool, errorText: string
     * }
     * 
     * @throws InvalidArgumentException Превышение максимального количества переданных номенклатур
     */
    public function upload(array $prices): object
    {
        $maxCount = 1_000;
        if (count($prices) > $maxCount) {
            throw new InvalidArgumentException("Превышение максимального количества переданных номенклатур: {$maxCount}");
        }
        return $this->postRequest('/api/v2/upload/task', [
            'data' => $prices,
        ]);
    }

    /**
     * Установить цены для размеров
     * 
     * За раз можно загрузить не более 1000 номенклатур.
     * Работает только для товаров из категорий, где можно устанавливать цены отдельно для разных размеров.
     * (Для таких товаров в ответе метода getPrices у артикулов editableSizePrice: true)
     * 
     * @param array $prices Размеры и цены для них.
     *                      [{nmID: int, sizeID: int, price: int}, ...]
     * 
     * @return object {
     *      data: {id: int, alreadyExists: bool}
     *      error: bool, errorText: string
     * }
     * 
     * @throws InvalidArgumentException Превышение максимального количества переданных номенклатур
     */
    public function uploadSizes(array $prices): object
    {
        $maxCount = 1_000;
        if (count($prices) > $maxCount) {
            throw new InvalidArgumentException("Превышение максимального количества переданных номенклатур: {$maxCount}");
        }
        return $this->postRequest('/api/v2/upload/task/size', [
            'data' => $prices,
        ]);
    }

    /**
     * Состояние обработанной загрузки
     * 
     * @param int $uploadId ID загрузки
     * 
     * @return object {data: object, error: bool, errorText: string}
     */
    public function getUploadStatus(int $uploadId): object
    {
        return $this->getRequest('/api/v2/history/tasks', [
            'uploadID' => $uploadId,
        ]);
    }
    
    /**
     * Состояние НЕобработанной загрузки
     * 
     * @param int $uploadId ID загрузки
     * 
     * @return object {data: object, error: bool, errorText: string}
     */
    public function getBufferUploadStatus(int $uploadId): object
    {
        return $this->getRequest('/api/v2/buffer/tasks', [
            'uploadID' => $uploadId,
        ]);
    }
    
    /**
     * Детализация обработанной загрузки
     * 
     * Возвращает информацию о товарах в обработанной загрузке, в том числе, об ошибках в них
     * 
     * @param int $uploadId ID загрузки
     * @param int $page     Номер страницы
     * @param int $onPage   Количество результатов на странице
     * 
     * @return object {data: {uploadID: ?int, historyGoods: ?array}, error: bool, errorText: string}
     * 
     * @throws InvalidArgumentException Превышение максимального размера страницы
     */
    public function getUpload(int $uploadId, int $page = 1, int $onPage = 1_000): object
    {
        $maxLimit = 1_000;
        if ($onPage > $maxLimit) {
            throw new InvalidArgumentException("Превышение максимального размера страницы: {$maxLimit}");
        }
        return $this->getRequest('/api/v2/history/goods/task', [
            'uploadID' => $uploadId,
            'offset' => --$page * $onPage,
            'limit' => $onPage,
        ]);
    }

    /**
     * Детализация НЕобработанной загрузки
     * 
     * Возвращает информацию о товарах из загрузки в обработке, в том числе, об ошибках в них
     * 
     * @param int $uploadId ID загрузки
     * @param int $page     Номер страницы
     * @param int $onPage   Количество результатов на странице
     * 
     * @return object {data: {uploadID: ?int, bufferGoods: ?array}, error: bool, errorText: string}
     * 
     * @throws InvalidArgumentException Превышение максимального размера страницы
     */
    public function getBufferUpload(int $uploadId, int $page = 1, int $onPage = 1_000): object
    {
        $maxLimit = 1_000;
        if ($onPage > $maxLimit) {
            throw new InvalidArgumentException("Превышение максимального размера страницы: {$maxLimit}");
        }
        return $this->getRequest('/api/v2/buffer/goods/task', [
            'uploadID' => $uploadId,
            'offset' => --$page * $onPage,
            'limit' => $onPage,
        ]);
    }
}
