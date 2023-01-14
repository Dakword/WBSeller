<?php

declare(strict_types=1);

namespace Dakword\WBSeller\API\Endpoint;

use Dakword\WBSeller\API\AbstractEndpoint;
use Dakword\WBSeller\Enum\AdvertStatus;
use Dakword\WBSeller\Enum\AdvertType;
use InvalidArgumentException;

class Adv extends AbstractEndpoint
{

    /**
     * Получение списка РК поставщика
     * 
     * @param int    $status    Статус РК
     * @param int    $type      Тип РК
     * @param int    $limit     Количество кампаний в ответе
     * @param int    $offset    Смещение относительно первой РК
     * @param string $order     Порядок: "create", "change", "id"
     * @param string $direction Направление: "desc", "asc"
     * 
     * @return array
     * 
     * @throws InvalidArgumentException Неизвестный статус РК
     * @throws InvalidArgumentException Неизвестный тип РК
     */
    public function advertsList(int $status, int $type, int $limit, int $offset = 0, string $order = 'change', string $direction = 'desc'): array
    {
        if (!in_array($status, AdvertStatus::all())) {
            throw new InvalidArgumentException('Неизвестный статус РК: ' . $status);
        }
        if (!in_array($type, AdvertType::all())) {
            throw new InvalidArgumentException('Неизвестный тип РК: ' . $type);
        }
        return $this->request('/adv/v0/adverts', 'GET', [
                'status' => $status,
                'type' => $type,
                'limit' => $limit,
                'offset' => $offset,
                'order' => $order,
                'direction' => $direction,
        ]);
    }

    /**
     * Получение информации об одной РК
     * 
     * @param int $id Идентификатор РК
     * 
     * @return object
     */
    public function advert(int $id): object
    {
        return $this->request('/adv/v0/advert', 'GET', ['id' => $id]);
    }

    /**
     * Получение количества РК поставщика
     * 
     * @return object
     */
    public function count(): object
    {
        return $this->request('/adv/v0/count');
    }

    /**
     * Получение списка ставок для типа размещения
     * 
     * @param int $type  Тип РК
     * @param int $param Параметр запроса, по которому будет получен список ставок активных РК.
     *                   Должен быть значением menuId, subjectId или setId в зависимости от типа РК.
     * 
     * @return array
     */
    public function cpm(int $type, int $param): array
    {
        return $this->request('/adv/v0/cpm', 'GET', [
            'type' => $type,
            'param' => $param,
        ]);
    }

    /**
     * Изменение ставки у РК.
     * Доступно для РК в карточке товара, поиске или рекомендациях.
     * 
     * @param int $advertId Идентификатор РК
     * @param int $type     Тип РК
     * @param int $cpm      Новое значение ставки
     * @param int $param    Параметр, для которго будет внесено изменение (является значением subjectId или setId в зависимости от типа РК)
     * 
     * @return bool
     * 
     * @throws InvalidArgumentException Недопустимый тип РК
     */
    public function updateCpm(int $advertId, int $type, int $cpm, int $param): bool
    {
        if (!in_array($type, [AdvertType::ON_CARD, AdvertType::ON_SEARCH, AdvertType::ON_HOME_RECOM])) {
            throw new InvalidArgumentException('Недопустимый тип РК: ' . $type);
        }
        $this->request('/adv/v0/cpm', 'POST', [
            'advertId' => $advertId,
            'type' => $type,
            'cpm' => $cpm,
            'param' => $param,
        ]);
        return $this->responseCode() == 200;
    }
    
    /**
     * Запуск РК
     * 
     * @param int $id
     * @return bool
     */
    public function start(int $id): bool
    {
        $this->request('/adv/v0/start', 'GET', ['id' => $id]);
        return $this->responseCode() == 200;
    }

    /**
     * Пауза РК
     * 
     * @param int $id
     * @return bool
     */
    public function pause(int $id): bool
    {
        $this->request('/adv/v0/pause', 'GET', ['id' => $id]);
        return $this->responseCode() == 200;
    }

}
