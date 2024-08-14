<?php

declare(strict_types=1);

namespace Dakword\WBSeller\API\Endpoint\Subpoint;

use Dakword\WBSeller\API\Endpoint\Marketplace;

class DBS
{
    private Marketplace $Marketplace;

    public function __construct(Marketplace $Marketplace)
    {
        $this->Marketplace = $Marketplace;
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
        $this->Marketplace->patchRequest("/api/v3/orders/{$order_id}/confirm");
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
        $this->Marketplace->patchRequest("/api/v3/orders/{$order_id}/deliver");
        return $this->Marketplace->responseCode() == 204;
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
        $this->Marketplace->patchRequest("/api/v3/orders/{$order_id}/receive");
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
        $this->Marketplace->patchRequest("/api/v3/orders/{$order_id}/reject");
        return $this->Marketplace->responseCode() == 204;
    }

    /**
     * Информация по клиенту
     */
    public function getOrdersClient(array $orders)
    {
        return $this->Marketplace->getOrdersClient($orders);
    }
}
