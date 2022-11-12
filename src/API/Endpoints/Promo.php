<?php

declare(strict_types=1);

namespace Dakword\WBSeller\API\Endpoints;

use Dakword\WBSeller\API\AbstractEndpoint;
use DateTime;

class Promo extends AbstractEndpoint
{

    /**
     * Установка скидок для номенклатур
     * 
     * Максимальное количество номенклатур на запрос - 1000
     * 
     * @param array    $discounts    {nm: integer, discount: int}, ...] 
     * @param DateTime $activateFrom Дата активации скидки.
     *                               Если не указывать, скидка начнет действовать сразу
     * 
     * @return object {uploadId: int, ?alreadyExists: bool}
     * @return object {errors: [string, ...]}
     * 
     * @throws InvalidArgumentException
     */
    public function updateDiscounts(array $discounts, DateTime $activateFrom = null): object
    {
        $maxCount = 1_000;
        if (count($discounts) > $maxCount) {
            throw new InvalidArgumentException("Превышение максимального количества переданных номенклатур: {$maxCount}");
        }
        return $this->request('/public/api/v1/updateDiscounts' . (!is_null($activateFrom) ? ('?activateFrom=' . $activateFrom->format('Y-m-d H:i:s')) : ''), 'POST', $discounts);
    }

    /**
     * Сброс скидок для номенклатур
     * 
     * @param array $nomenclatures Перечень номенклатур к отмене скидок
     * 
     * @return string При первом запросе ответ будет пустым.
     * @return object При повторной попытке сбросить скидку вернет JSON с id первичного запроса.
     *                {uploadId: int, alreadyExists: bool}
     */
    public function revokeDiscounts(array $nomenclatures)
    {
        return $this->request('/public/api/v1/revokeDiscounts', 'POST', $nomenclatures);
    }

}
