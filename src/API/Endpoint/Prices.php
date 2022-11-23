<?php

declare(strict_types=1);

namespace Dakword\WBSeller\API\Endpoint;

use Dakword\WBSeller\API\AbstractEndpoint;
use InvalidArgumentException;

class Prices extends AbstractEndpoint
{

    /**
     * Получение информации по номенклатурам, их ценам, скидкам и промокодам.
     * 
     * @param int $quantity Фильтр: 2 - товар с нулевым остатком, 1 - товар с ненулевым остатком, 0 - товар с любым остатком
     * 
     * @return array [{nmId: int, price: number, discount: int, promoCode: number}, ...]
     * 
     * @throws InvalidArgumentException
     */
    private function getPricesInfo(int $quantity = 0): array
    {
        if (!in_array($quantity, [0, 1, 2])) {
            throw new InvalidArgumentException('Задан некорректный параметр фильтрации: ' . $quantity);
        }
        return $this->request('/public/api/v1/info', 'GET', ['quantity' => $quantity]);
    }

    /**
     * Получение информации по ВСЕМ номенклатурам, их ценам, скидкам и промокодам.
     * 
     * @return array [{nmId: int, price: number, discount: int, promoCode: number}, ...]
     */
    public function getPrices(): array
    {
        return $this->getPricesInfo();
    }

    /**
     * Получение информации по номенклатурам, их ценам, скидкам и промокодам.
     * Для товаров с ненулевым остатком
     * 
     * @return array [{nmId: int, price: number, discount: int, promoCode: number}, ...]
     */
    public function getPricesOnStock(): array
    {
        return $this->getPricesInfo(1);
    }

    /**
     * Получение информации по номенклатурам, их ценам, скидкам и промокодам.
     * Для товаров с нулевым остатком
     * 
     * @return array [{nmId: int, price: number, discount: int, promoCode: number}, ...]
     */
    public function getPricesNoStock(): array
    {
        return $this->getPricesInfo(2);
    }

    /**
     * Загрузка цен
     * За раз можно загрузить не более 1000 номенклатур.
     * 
     * @param array $prices [{nmId: int, price: int}, ...]
     * 
     * @return object {uploadId: int}
     * @return object {errors: [string, ...]}
     * 
     * @throws InvalidArgumentException
     */
    public function updatePrices(array $prices): object
    {
        $maxCount = 1_000;
        if (count($prices) > $maxCount) {
            throw new InvalidArgumentException("Превышение максимального количества переданных номенклатур: {$maxCount}");
        }
        return $this->request('/public/api/v1/prices', 'POST', $prices);
    }

}
