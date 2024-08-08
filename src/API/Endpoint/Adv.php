<?php

declare(strict_types=1);

namespace Dakword\WBSeller\API\Endpoint;

use Dakword\WBSeller\API\AbstractEndpoint;
use Dakword\WBSeller\API\Endpoint\Subpoint\AdvAuto;
use Dakword\WBSeller\API\Endpoint\Subpoint\AdvSearchCatalog;
use Dakword\WBSeller\API\Endpoint\Subpoint\AdvFinance;
use Dakword\WBSeller\Enum\AdvertType;
use InvalidArgumentException;

class Adv extends AbstractEndpoint
{
    /**
     * Сервисы для автоматических кампаний
     *
     * @return AdvAuto
     */
    public function Auto(): AdvAuto
    {
        return new AdvAuto($this);
    }

    /**
     * Сервисы для финансов
     *
     * @return AdvFinance
     */
    public function Finances(): AdvFinance
    {
        return new AdvFinance($this);
    }

    /**
     * Сервисы для кампаний в поиске и поиск + каталог
     *
     * @return AdvSearchCatalog
     */
    public function SearchCatalog(): AdvSearchCatalog
    {
        return new AdvSearchCatalog($this);
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
     * Списки кампаний
     *
     * Метод позволяет получать списки кампаний, сгруппированных по типу и статусу,
     * с информацией о дате последнего изменения кампании.
     * Допускается 5 запросов в секунду.
     * @link https://openapi.wb.ru/promotion/api/ru/#tag/Prodvizhenie/paths/~1adv~1v1~1promotion~1count/get
     *
     * @return array Данные по кампаниям
     */
    public function advertsList(): array
    {
        return $this->getRequest('/adv/v1/promotion/count')->adverts ?? [];
    }

    /**
     * Удаление кампании
     *
     * Метод позволяет удалять кампании в статусе 4 - готова к запуску.
     * Допускается 5 запросов в секунду.
     * После удаления кампания некоторое время будет находиться в -1 статусе.
     * Полное удаление кампании занимает от 3 до 10 минут.
     * @link https://openapi.wb.ru/promotion/api/ru/#tag/Prodvizhenie/paths/~1adv~1v0~1delete/get
     *
     * @param int $id ID кампании
     *
     * @return bool
     */
    public function delete(int $id): bool
    {
        $this->getRequest('/adv/v0/delete', [
            'id' => $id,
        ]);
        return $this->responseCode() == 200;
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
        $this->postRequest('/adv/v0/rename', [
            'advertId' => $advertId,
            'name' => mb_substr($name, 0, 100)
        ]);
        return $this->responseCode() == 200;
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

    /**
     * Изменение ставки у РК.
     *
     * Доступно для РК в карточке товара, поиске или рекомендациях.
     *
     * @param int $advertId   Идентификатор РК
     * @param int $type       Тип РК
     * @param int $cpm        Новое значение ставки
     * @param int $param      Параметр, для которого будет внесено изменение
     *                        (является значением subjectId или setId в зависимости от типа РК)
     * @param int $instrument Тип кампании для изменения ставки в Поиск + Каталог (4 - каталог, 6 - поиск)
     *
     * @return bool
     *
     * @throws InvalidArgumentException Недопустимый тип РК
     */
    public function updateCpm(int $advertId, int $type, int $cpm, int $param, int $instrument): bool
    {
        $this->checkType($type, [AdvertType::ON_CARD, AdvertType::ON_SEARCH, AdvertType::ON_HOME_RECOM]);
        $this->postRequest('/adv/v0/cpm', [
            'advertId' => $advertId,
            'type' => $type,
            'cpm' => $cpm,
            'param' => $param,
            'instrument' => $instrument,
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
     * Изменение активности номенклатур в РК.
     *
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

    private function checkType(int $type, array $types = [])
    {
        if (!in_array($type, $types ?: AdvertType::all())) {
            throw new InvalidArgumentException('Неизвестный тип РК: ' . $type);
        }
    }
}
