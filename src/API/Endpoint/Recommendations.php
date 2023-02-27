<?php

declare(strict_types=1);

namespace Dakword\WBSeller\API\Endpoint;

use Dakword\WBSeller\API\AbstractEndpoint;

class Recommendations extends AbstractEndpoint
{

    /**
     * Получение списка рекомендаций
     * 
     * Метод позволяет получить список рекомендаций ("Магазин рекомендует") по конкретному товару.
     * 
     * @param int $nmId Идентификатор товара, по которому необходимо получить список рекомендаций
     * 
     * @return array
     */
    public function list(int $nmId): array
    {
        return $this->request('/api/v1/sup', ['nm' => $nmId]);
    }

    /**
     * Добавление рекомендаций
     * 
     * Метод позволяет добавить рекомендации к товарам.
     * 
     * @param array $recom [nmId => [nmId1, nmId2, ...], ...]
     *                      nmId - Идентификатор товара, к которому добавляется рекомендация
     *                      [nmId1, nmId2, ...] - Список идентификаторов товаров,
     *                                            которые необходимо добавить в рекомендуемые
     * 
     * @return string
     */
    public function add(array $recom): string
    {
        return $this->request('/api/v1/ins', array_map(
            function ($key, $value) {
                return ['nm' => (int) $key, 'recom' => $value];
            },
            array_keys($recom), array_values($recom)
        ), 'POST');
    }

    /**
     * Удаление рекомендаций
     * 
     * Метод позволяет удалить рекомендации.
     * 
     * @param array $recom [nmId => [nmId1, nmId2, ...], ...]
     *                      nmId - Идентификатор товара, у которого необходимо удалить рекомендацию
     *                      [nmId1, nmId2, ...] - Список идентификаторов товаров,
     *                                            которые необходимо удалить из рекомендуемых
     * 
     * @return string
     */
    public function delete(array $recom): string
    {
        return $this->request('/api/v1/del', array_map(
            function ($key, $value) {
                return ['nm' => (int) $key, 'recom' => $value];
            },
            array_keys($recom), array_values($recom)
        ), 'POST');
    }

}
