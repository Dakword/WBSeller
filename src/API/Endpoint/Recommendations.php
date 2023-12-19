<?php

declare(strict_types=1);

namespace Dakword\WBSeller\API\Endpoint;

use Dakword\WBSeller\API\AbstractEndpoint;
use InvalidArgumentException;

class Recommendations extends AbstractEndpoint
{

    /**
     * Получение списка рекомендаций
     * 
     * Метод позволяет получить список рекомендаций ("Магазин рекомендует") по нескольким товарам.
     * 
     * @param array $nmIds Идентификаторы товаров, для которых необходимо получить список рекомендаций (max. 200)
     * @param int   $limit Ограничение количества рекомендованных nm в ответе (max. 999)
     * 
     * @return array !!! РЕСТРУКТУРИРОВАННЫЙ ОТВЕТ
     *               [
     *                  nmId => [recomNm1, recomNm2, ...],
     *                  ...
     *               ]
     * 
     * @throws InvalidArgumentException Превышение максимального количества переданных идентификаторов
     * @throws InvalidArgumentException Превышение максимального значения параметра limit
     */
    public function list(array $nmIds, int $limit = 0): array
    {
        $maxCount = 200;
        if (count($nmIds) > $maxCount) {
            throw new InvalidArgumentException("Превышение максимального количества переданных идентификаторов: {$maxCount}");
        }
        $maxLimit = 999;
        if ($limit >  $maxLimit) {
            throw new InvalidArgumentException("Превышение максимального значения параметра limit: {$maxLimit}");
        }
        $result = $this->postRequest('/api/v1/list', $nmIds, ['limit' => $limit]);
        return array_reduce($result->data, function ($result, $item) {
            $result[$item->nm] = $item->list;
            return $result;
        });
    }

    /**
     * Добавление рекомендаций
     * 
     * Метод позволяет добавить рекомендации к товарам.
     * 
     * @param array $recom [nmId => [nmId1, nmId2, ...], ...]
     *                     nmId - Идентификатор товара, к которому добавляется рекомендация
     *                     [nmId1, nmId2, ...] - Список идентификаторов товаров,
     *                                           которые необходимо добавить в рекомендуемые
     * 
     * @return object|string
     */
    public function add(array $recom)
    {
        return $this->postRequest('/api/v1/ins', array_map(
            function ($key, $value) {
                return ['nm' => (int) $key, 'recom' => $value];
            },
            array_keys($recom), array_values($recom)
        ));
    }

    /**
     * Удаление рекомендаций
     * 
     * Метод позволяет удалить рекомендации.
     * 
     * @param array $recom [nmId => [nmId1, nmId2, ...], ...]
     *                     nmId - Идентификатор товара, у которого необходимо удалить рекомендацию
     *                     [nmId1, nmId2, ...] - Список идентификаторов товаров,
     *                                           которые необходимо удалить из рекомендуемых
     * 
     * @return string|object
     */
    public function delete(array $recom)
    {
        return $this->postRequest('/api/v1/del', array_map(
            function ($key, $value) {
                return ['nm' => (int) $key, 'recom' => $value];
            },
            array_keys($recom), array_values($recom)
        ));
    }

    /**
     * Управление рекомендациями
     * 
     * Метод позволяет добавлять, удалять рекомендации.
     * Работает по принципу перезаписи, все что указано в recom, ставится взамен того, что было ранее.
     * Чтобы удалить рекомендации необходимо передать пустой массив recom.
     * 
     * @param array $recom [nmId => [nmId1, nmId2, ...], ...]
     *                     nmId - Идентификатор товара
     *                     [nmId1, nmId2, ...] - Список идентификаторов товаров,
     *                                           которые необходимо передать в рекомендуемые
     * 
     * @return string|object
     */
    public function update(array $recom)
    {
        return $this->postRequest('/api/v1/set', array_map(
            function ($key, $value) {
                return ['nm' => (int) $key, 'recom' => $value];
            },
            array_keys($recom), array_values($recom)
        ));
    }
    
}
