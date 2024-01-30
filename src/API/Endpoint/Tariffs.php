<?php

declare(strict_types=1);

namespace Dakword\WBSeller\API\Endpoint;

use Dakword\WBSeller\API\AbstractEndpoint;
use DateTime;

class Tariffs extends AbstractEndpoint
{

    /**
     * Тарифы для коробов
     * 
     * Для товаров, которые поставляются на склад в коробах (коробках), возвращает стоимость:
     *  - доставки со склада или пункта приёма до покупателя;
     *  - доставки от покупателя до пункта приёма;
     *  - хранения на складе Wildberries.
     * 
     * Максимум — 60 запросов в минуту.
     * 
     * @param DateTime $date Дата
     * 
     * @return object {dtFromMin: string, dtNextBox: string, dtTillMax: string, warehouseList: array}
     */
    public function box(DateTime $date): object
    {
        return $this->getRequest('/api/v1/tariffs/box', [
            'date' => $date->format('Y-m-d'),
        ])
        ->response->data;
    }

    /**
     * Тарифы для монопалет
     * 
     * Для товаров, которые поставляются на склад Wildberries на монопалетах, возвращает стоимость:
     *  - доставки со склада до покупателя;
     *  - доставки от покупателя до склада;
     *  - хранения на складе Wildberries.
     * 
     * Максимум — 60 запросов в минуту.
     * 
     * @param DateTime $date Дата
     * 
     * @return object {dtFromMin: string, dtNextPallet: string, dtTillMax: string, warehouseList: array}
     */
    public function pallet(DateTime $date): object
    {
        return $this->getRequest('/api/v1/tariffs/pallet', [
            'date' => $date->format('Y-m-d'),
        ])
        ->response->data;
    }

    /**
     * Тарифы на возврат
     * 
     * Возвращает тарифы:
     *  - на перевозку товаров со склада Wildberries или из пункта приёма до продавца;
     *  - на обратную перевозку возвратов, которые не забрал продавец.
     * 
     * Максимум — 60 запросов в минуту.
     * 
     * @param DateTime $date Дата
     * 
     * @return object {dtNextDeliveryDumpKgt: string, dtNextDeliveryDumpSrg: string, dtNextDeliveryDumpSup: string, warehouseList: array}
     */
    public function return(DateTime $date): object
    {
        return $this->getRequest('/api/v1/tariffs/return', [
            'date' => $date->format('Y-m-d'),
        ])
        ->response->data;
    }

}
