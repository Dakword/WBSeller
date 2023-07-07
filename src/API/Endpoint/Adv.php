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
        $this->checkType($type);
        if (!in_array($order, ["create", "change", "id"])) {
            throw new InvalidArgumentException('Неизвестный порядок сортировки: ' . $order);
        }
        return $this->getRequest('/adv/v0/adverts', [
            'status' => $status,
            'type' => $type,
            'limit' => $limit,
            'offset' => $offset,
            'order' => $order,
            'direction' => $direction,
        ]) ?? [];
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
        return $this->getRequest('/adv/v0/advert', ['id' => $id]);
    }

    /**
     * Получение количества РК поставщика
     * 
     * @return object
     */
    public function count(): object
    {
        return $this->getRequest('/adv/v0/count');
    }

    /**
     * Переименование РК
     * 
     * @param type $advertId Идентификатор РК, у которой меняется название
     * @param type $name     Новое название (максимум 100 символов)
     * 
     * @return bool
     */
    public function renameAdvert($advertId, $name): bool
    {
        return $this->postRequest('/adv/v0/rename', [
            'advertId' => $advertId,
            'name' => mb_substr($name, 0, 100)
        ]);
    }
    
    /**
     * Получение списка ставок для типа размещения
     * 
     * @param int $type  Тип РК
     * @param int $param Параметр запроса, по которому будет получен список ставок активных РК.
     *                   Должен быть значением menuId (для РК в каталоге), subjectId (для РК в поиске и рекомендациях)
     *                   или setId (для РК в карточке товара).
     * 
     * @return array
     * 
     * @throws InvalidArgumentException Неизвестный тип РК
     */
    public function cpm(int $type, int $param): array
    {
        $this->checkType($type);
        return $this->getRequest('/adv/v0/cpm', [
            'type' => $type,
            'param' => $param,
        ]);
    }

    /**
     * Изменение ставки у РК.
     * 
     * Доступно для РК в карточке товара, поиске или рекомендациях.
     * 
     * @param int $advertId Идентификатор РК
     * @param int $type     Тип РК
     * @param int $cpm      Новое значение ставки
     * @param int $param    Параметр, для которого будет внесено изменение (является значением subjectId или setId в зависимости от типа РК)
     * 
     * @return bool
     * 
     * @throws InvalidArgumentException Недопустимый тип РК
     */
    public function updateCpm(int $advertId, int $type, int $cpm, int $param): bool
    {
        $this->checkType($type, [AdvertType::ON_CARD, AdvertType::ON_SEARCH, AdvertType::ON_HOME_RECOM]);
        $this->postRequest('/adv/v0/cpm', [
            'advertId' => $advertId,
            'type' => $type,
            'cpm' => $cpm,
            'param' => $param,
        ]);
        return $this->responseCode() == 200;
    }

    /**
     * Получение списка ставок по типу размещения РК
     * 
     * @param int   $type  Тип РК
     * @param array $param Параметр запроса, по которому будет получен список ставок активных РК.
     *                     Должен быть значением menuId (для РК в каталоге), subjectId (для РК в поиске и рекомендациях)
     *                     или setId (для РК в карточке товара).
     * 
     * @return array
     */
    public function allCpm(int $type, array $param): array
    {
        $this->checkType($type);
        return $this->postRequest('/adv/v0/allcpm?type=' . $type, [
            'param' => $param,
        ]);
    }

    /**
     * Изменение активности предметной группы для РК в поиске
     * 
     * @param int  $id        Идентификатор РК
     * @param int  $subjectId Идентификатор предметной группы, для которой меняется активность
     * @param bool $status    Новое состояние
     *                        true - сделать группу активной
     *                        false - сделать группу неактивной
     * 
     * @return bool
     */
    public function setActive(int $id, int $subjectId, bool $status): bool
    {
        $this->getRequest('/adv/v0/active', [
            'id' => $id,
            'subjectId' => $subjectId,
            'status' => $status,
        ]);
        return $this->responseCode() == 200;
    }
 
    /**
     *  Изменение временных интервалов показа рекламной кампании
     * 
     * @param int   $advertId  Идентификатор РК, у которой меняется интервал
     * @param int   $param     Параметр, для которого будет внесено изменение,
     *                         должен быть значением menuId (для РК в каталоге), subjectId (для РК в поиске и рекомендациях)
     *                         или setId (для РК в карточке товара)
     * @param array $intervals Массив новых значений для интервалов
     *                         Максимальное количество интервалов 24.
     *                         [{"begin": 15, "end": 21}, ...]
     *                         begin, end - Время начала и окончания показов, по 24 часовой схеме
     * 
     * @return bool
     * 
     * @throws InvalidArgumentException Превышение максимального количества переданных интервалов
     */
    public function setIntervals(int $advertId, int $param, array $intervals): bool
    {
        $maxCount = 24;
        if (count($intervals) > $maxCount) {
            throw new InvalidArgumentException("Превышение максимального количества переданных интервалов: {$maxCount}");
        }
        $this->postRequest('/adv/v0/intervals', [
            'advertId' => $advertId,
            'param' => $param,
            'intervals' => $intervals,
        ]);
        return $this->responseCode() == 200;
    }
    
    /**
     * Изменение размера дневного бюджета рекламной кампании
     * 
     * @param int $advertId Идентификатор РК, у которой меняется бюджет
     * @param int $budget   Сумма дневного бюджета
     *                      Значение должно быть больше 500 или равно 0 в случае, если бюджет не установлен
     * 
     * @return bool
     * 
     * @throws InvalidArgumentException Некорректное значение суммы дневного бюджета
     * 
     * @deprecated since 22/05/2023
     */
    public function dailyBudget(int $advertId, int $budget): bool
    {
        if ($budget > 0 && $budget <= 500 || $budget < 0) {
            throw new InvalidArgumentException('Некорректное значение суммы дневного бюджета');
        }
        $this->postRequest('/adv/v0/dailybudget', [
            'advertId' => $advertId,
            'dailyBudget' => $budget,
        ]);
        return $this->responseCode() == 200;
    }

    /**
     * Изменение активности номенклатур в РК.
     * В запросе необходимо передавать все номенклатуры РК с их активностью,
     * даже если изменение планируется только по одной номенклатуре.
     * При наличии в РК нескольких subjectId номенклатуры по каждому subjectId необходимо передать отдельным запросом.
     * То же касается setId, menuId.
     * 
     * @param int   $advertId Идентификатор РК, у которой меняется бюджет
     * @param int   $param    Параметр, для которого будет внесено изменение,
     *                        должен быть значением menuId (для РК в каталоге), subjectId (для РК в поиске и рекомендациях)
     *                        или setId (для РК в карточке товара)
     * @param array $active   Массив значений активности для номенклатур.
     *                        Максимальноe количество номенклатур в запросе 50.
     *                        [{"nm": 2116745, "active": true}, ...]
     * 
     * @return bool
     * 
     * @throws InvalidArgumentException Превышение максимального количества номенклатур в запросе
     */
    public function nmActive(int $advertId, int $param, array $active): bool
    {
        $maxCount = 50;
        if (count($active) > $maxCount) {
            throw new InvalidArgumentException("Превышение максимального количества номенклатур в запросе: {$maxCount}");
        }
        $this->postRequest('/adv/v0/nmactive', [
            'advertId' => $advertId,
            'param' => $param,
            'active' => $active,
        ]);
        return $this->responseCode() == 200;
    }
    
    /**
     * Словарь значений параметра subjectId
     * 
     * Метод позволяет получить список значений параметра subjectId.
     * 
     * @param int $id Идентификатор предметной группы, для которой создана РК (для РК в поиске и рекомендациях).
     *                Принимает значение параметра subjectId из РК.
     *                При пустом параметре вернётся весь список существующих значений.
     * 
     * @return array
     */
    public function paramSubject(int $id = 0): array
    {
        return $this->getRequest('/adv/v0/params/subject', $id ? ['id' => $id] : []);
    }
    
    /**
     * Словарь значений параметра menuId
     * 
     * Метод позволяет получить список значений параметра menuId.
     * 
     * @param int $id Идентификатор меню, где размещается РК (для РК в каталоге).
     *                Принимает значение параметра menuId из РК.
     *                При пустом параметре вернётся весь список существующих значений.
     * 
     * @return array
     */
    public function paramMenu(int $id = 0): array
    {
        return $this->getRequest('/adv/v0/params/menu', $id ? ['id' => $id] : []);
    }
    
    /**
     * Словарь значений параметра setId
     * 
     * Метод позволяет получить список значений параметра setId
     * 
     * @param int $id Идентификатор сочетания предмета и пола (для РК в карточке товара).
     *                Принимает значение параметра setId из РК.
     *                При пустом параметре вернётся весь список существующих значений.
     * 
     * @return array
     */
    public function paramSet(int $id = 0): array
    {
        return $this->getRequest('/adv/v0/params/set', $id ? ['id' => $id] : []);
    }
    
    /**
     * Запуск РК
     * 
     * @param int $id
     * 
     * @return bool
     */
    public function start(int $id): bool
    {
        $this->getRequest('/adv/v0/start', ['id' => $id]);
        return $this->responseCode() == 200;
    }

    /**
     * Пауза РК
     * 
     * @param int $id
     * 
     * @return bool
     */
    public function pause(int $id): bool
    {
        $this->getRequest('/adv/v0/pause', ['id' => $id]);
        return $this->responseCode() == 200;
    }

    /**
     * Пауза РК
     * 
     * @param int $id
     * 
     * @return bool
     */
    public function stop(int $id): bool
    {
        $this->getRequest('/adv/v0/stop', ['id' => $id]);
        return $this->responseCode() == 200;
    }

    /**
     * Баланс
     * 
     * Метод позволяет получать информацию о счёте, балансе и бонусах продавца
     * 
     * @return object {balance: int, net: int, bonus: int}
     */
    public function balance(): object
    {
        return $this->getRequest('/adv/v1/balance');
    }

    private function checkType(int $type, array $types = [])
    {
        if (!in_array($type, $types ?: AdvertType::all())) {
            throw new InvalidArgumentException('Неизвестный тип РК: ' . $type);
        }
    }
    
}
