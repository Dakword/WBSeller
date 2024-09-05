<?php

declare(strict_types=1);

namespace Dakword\WBSeller\API\Endpoint\Subpoint;

use Dakword\WBSeller\API\Endpoint\Common;

class News
{
    private Common $Common;

    public function __construct(Common $Common)
    {
        $this->Common = $Common;
    }

    /**
     * Новости портала продавцов
     *
     * Метод позволяет получать новости с портала продавцов в формате HTML.
     * За один запрос можно получить не более 100 новостей.
     * @link https://openapi.wildberries.ru/general/sellers_portal_news/ru/#/paths/~1api~1communications~1v1~1news/get
     *
     * @param \DateTime $date Дата, от которой необходимо выдать новости
     *
     * @return array [object, object, ...]
     */
    public function fromDate(\DateTime $date): array
    {
        return $this->Common->getRequest('/api/communications/v1/news', [
            'from' => $date->format('Y-m-d'),
        ])->data;
    }

    /**
     * Новости портала продавцов
     *
     * Метод позволяет получать новости с портала продавцов в формате HTML.
     * За один запрос можно получить не более 100 новостей.
     * Допускается 1 запрос в 10 минут.
     * @link https://openapi.wildberries.ru/general/sellers_portal_news/ru/#/paths/~1api~1communications~1v1~1news/get
     *
     * @param int $id ID новости, от которой необходимо выдать новости
     *
     * @return array [object, object, ...]
     */
    public function fromId(int $id): array
    {
        return $this->Common->getRequest('/api/communications/v1/news', [
            'fromID' => $id,
        ])->data;
    }
}
