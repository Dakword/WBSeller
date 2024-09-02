<?php

declare(strict_types=1);

namespace Dakword\WBSeller\API\Endpoint\Subpoint;

use Dakword\WBSeller\API\Endpoint\Content;

class News
{
    private Content $Content;

    public function __construct(Content $Content)
    {
        $this->Content = $Content;
    }

    /**
     * Новости портала продавцов
     *
     * Метод позволяет получать новости с портала продавцов в формате HTML.
     * За один запрос можно получить не более 100 новостей.
     *
     * @param \DateTime $date Дата, от которой необходимо выдать новости
     *
     * @return array [object, object, ...]
     */
    public function fromDate(\DateTime $date): array
    {
        return $this->Content->getRequest('/api/communications/v1/news', [
            'from' => $date->format('Y-m-d'),
        ])->data;
    }

    /**
     * Новости портала продавцов
     *
     * Метод позволяет получать новости с портала продавцов в формате HTML.
     * За один запрос можно получить не более 100 новостей.
     * Допускается 1 запрос в 10 минут.
     *
     * @param int $id ID новости, от которой необходимо выдать новости
     *
     * @return array [object, object, ...]
     */
    public function fromId(int $id): array
    {
        return $this->Content->getRequest('/api/communications/v1/news', [
            'fromID' => $id,
        ])->data;
    }
}
