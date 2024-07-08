<?php

declare(strict_types=1);

namespace Dakword\WBSeller\API\Endpoint\Subpoint;

use Dakword\WBSeller\API\Endpoint\Adv;
use Dakword\WBSeller\Enum\AdvertStatus;
use Dakword\WBSeller\Enum\AdvertType;
use InvalidArgumentException;

class Promotion
{
    private Adv $Adv;

    public function __construct(Adv $Adv)
    {
        $this->Adv = $Adv;
    }

    /**
     * Информация о кампаниях
     * 
     * @param int    $status    Статус РК
     * @param int    $type      Тип РК
     * @param string $order     Порядок: "create", "change", "id"
     * @param string $direction Направление: "desc", "asc"
     * 
     * @return array
     * 
     * @throws InvalidArgumentException Неизвестный статус РК
     * @throws InvalidArgumentException Неизвестный тип РК
     * @throws InvalidArgumentException Неизвестный порядок сортировки
     */
    public function advertsInfo(int $status, int $type, string $order = 'change', string $direction = 'desc'): array
    {
        if (!in_array($status, AdvertStatus::all())) {
            throw new InvalidArgumentException('Неизвестный статус РК: ' . $status);
        }
        $this->checkType($type);
        if (!in_array($order, ["create", "change", "id"])) {
            throw new InvalidArgumentException('Неизвестный порядок сортировки: ' . $order);
        }
        return $this->postRequest('/adv/v1/promotion/adverts?' . http_build_query([
            'status' => $status,
            'type' => $type,
            'order' => $order,
            'direction' => $direction,
        ])) ?? [];
    }
    
    /**
     * Информация о кампаниях по списку их id.
     * 
     * @param array $ids Список ID кампаний. Максимум 50
     * 
     * @return array
     * 
     * @throws InvalidArgumentException Превышение максимального количества запрашиваемых кампаний
     */
    public function advertsInfoByIds(array $ids): array
    {
        $maxCount = 50;
        if (count($ids) > $maxCount) {
            throw new InvalidArgumentException("Превышение максимального количества запрашиваемых кампаний: {$maxCount}");
        }
        return $this->postRequest('/adv/v1/promotion/adverts', $ids) ?? [];
    }
    
    private function checkType(int $type, array $types = [])
    {
        if (!in_array($type, $types ?: AdvertType::all())) {
            throw new InvalidArgumentException('Неизвестный тип РК: ' . $type);
        }
    }
}
