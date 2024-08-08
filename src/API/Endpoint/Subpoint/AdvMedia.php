<?php

declare(strict_types=1);

namespace Dakword\WBSeller\API\Endpoint\Subpoint;

use Dakword\WBSeller\API\Endpoint\Adv;
use Dakword\WBSeller\Enum\MediaAdvertStatus;
use InvalidArgumentException;

class AdvMedia
{
    private Adv $Adv;

    public function __construct(Adv $Adv)
    {
        $this->Adv = $Adv;
    }

    /**
     * Получение количества медиакампаний поставщика
     *
     * @link https://openapi.wb.ru/promotion/api/ru/#tag/Media/paths/~1adv~1v1~1count/get
     *
     * @return object
     */
    public function count(): object
    {
        return $this->Adv->getRequest('/adv/v1/count');
    }

    /**
     * Получение информации об одной медиакампании
     *
     * @link https://openapi.wb.ru/promotion/api/ru/#tag/Media/paths/~1adv~1v1~1advert/get
     *
     * @param int $id Идентификатор медиакампании
     *
     * @return object
     */
    public function getAdvert(int $id): object
    {
        return $this->Adv->getRequest('/adv/v1/advert', ['id' => $id]);
    }

    /**
     * Список медиакампаний
     *
     * @link https://openapi.wb.ru/promotion/api/ru/#tag/Media/paths/~1adv~1v1~1adverts/get
     *
     * @param int    $status         Статус медиакампании (static::MediaAdvertStatus)
     * @param int    $type           Тип медиакампании: 1 - размещение по дням, 2 - размещение по просмотрам
     * @param int    $page           Номер страницы
     * @param int    $limit          Количество результатов на странице
     * @param string $orderBy        Порядок вывода ответа: create - по времени создания медиакампании
     *                                                      id - по идентификатору медиакампании
     * @param string $orderDirection Порядок сортировки: desc - от большего к меньшему
     *                                                   asc - от меньшего к большему
     *
     * @return array
     *
     * @throws InvalidArgumentException Неизвестный статус медиакампании
     * @throws InvalidArgumentException Неизвестный тип медиакампании
     * @throws InvalidArgumentException Неизвестный порядок вывода результатов
     * @throws InvalidArgumentException Неизвестный порядок сортировки результатов
     */
    public function advertsList(int $status, int $type, int $page, int $limit, string $orderBy = 'create', string $orderDirection = 'desc'): array
    {
        if (!in_array($status, MediaAdvertStatus::all())) {
            throw new InvalidArgumentException('Неизвестный статус медиакампании: ' . $status);
        }
        if (!in_array($type, [1, 2])) {
            throw new InvalidArgumentException('Неизвестный тип медиакампании: ' . $type);
        }
        if (!in_array($orderBy, ['id', 'create'])) {
            throw new InvalidArgumentException('Неизвестный порядок вывода результатов: ' . $orderBy);
        }
        if (!in_array($orderDirection, ['asc', 'desc'])) {
            throw new InvalidArgumentException('Неизвестный порядок сортировки результатов: ' . $orderDirection);
        }
        return $this->Adv->getRequest('/adv/v1/adverts', [
            'status' => $status,
            'type' => $type,
            'order' => $orderBy,
            'direction' => $orderDirection,
            'limit' => $limit,
            'offset' => --$page * $limit,
        ]);
    }

    /*
     * АКТИВНОСТЬ
     * --------------------------------------------------------------------------
     * @link https://openapi.wb.ru/promotion/api/ru/#tag/Aktivnost-mediakampanii
     */

    /**
     * Запуск медиакампании
     *
     * Метод позволяет запускать приостановленные медиакампании.
     * После запуска кампания в течение 2-5 минут будет находиться в статусе 4,
     * после чего статус будет изменён на актуальный, в зависимости от конфигурации медиакампании.
     * Допускается максимум 10 запросов в минуту.
     * @link https://openapi.wb.ru/promotion/api/ru/#tag/Aktivnost-mediakampanii/paths/~1adv~1v1~1advert~1start/post
     *
     * @param int    $id     ID медиакампании
     * @param string $reason Описание причины запуска
     *
     * @return bool
     */
    public function start(int $id, string $reason = ''): bool
    {
        $this->Adv->postRequest('/adv/v1/advert/start', [
            'advert_id' => $id,
            'reason' => $reason,
        ]);
        return $this->Adv->responseCode() == 200;
    }

    /**
     * Приостановка медиакампании
     *
     * Переводит приостанавливаемую медиакампанию в статус 9.
     * Допускается максимум 10 запросов в минуту.
     * Важно: приостановить медиакампанию можно не больше 10 раз в сутки.
     *        Сутки отсчитываются с полуночи по Московскому времени.
     * @link https://openapi.wb.ru/promotion/api/ru/#tag/Aktivnost-mediakampanii/paths/~1adv~1v1~1advert~1pause/post
     *
     * @param int    $id     ID медиакампании
     * @param string $reason Описание причины приостановки
     *
     * @return bool
     */
    public function pause(int $id, string $reason = ''): bool
    {
        $this->Adv->postRequest('/adv/v1/advert/pause', [
            'advert_id' => $id,
            'reason' => $reason,
        ]);
        return $this->Adv->responseCode() == 200;
    }

    /**
     * Завершение медиакампании
     *
     * Метод завершает медиакампанию - переводит её в статус 7.
     * Допускается максимум 10 запросов в минуту.
     * @link https://openapi.wb.ru/promotion/api/ru/#tag/Aktivnost-mediakampanii/paths/~1adv~1v1~1advert~1stop/post
     *
     * @param int    $id     ID медиакампании
     * @param string $reason Описание причины завершения
     *
     * @return bool
     */
    public function stop(int $id, string $reason = ''): bool
    {
        $this->Adv->postRequest('/adv/v1/advert/stop', [
            'advert_id' => $id,
            'reason' => $reason,
        ]);
        return $this->Adv->responseCode() == 200;
    }

    /*
     * СТАТИСТИКА
     * --------------------------------------------------------------------------
     * @link https://openapi.wb.ru/promotion/api/ru/#tag/Statistika-mediakampanii
     */

    /**
     * Статистика медиакампаний
     *
     * @link https://openapi.wb.ru/promotion/api/ru/#tag/Statistika-mediakampanii/paths/~1adv~1v1~1stats/post
     *
     * @param array $params Запрос с датами
     *                      Запрос с интервалами
     *                      Запрос с интервалами и датами
     *                      Запрос без параметров
     *
     * @return array
     *
     * @throws InvalidArgumentException Неизвестный тип списания
     */
    public function statistic(array $params): array
    {
        $maxItems = 100;
        if (count($params) > $maxItems) {
            throw new InvalidArgumentException("Превышение максимального количества переданных элементов: {$maxItems}");
        }
        return $this->Adv->postRequest('/adv/v1/stats', $params);
    }

    public function statisticByIds(array $params): array
    {
        return $this->statistic($params);
    }

    public function statisticByDates(array $params): array
    {
        return $this->statistic($params);
    }

    public function statisticByPeriod(array $params): array
    {
        return $this->statistic($params);
    }

    /*
     * СТАВКИ
     * --------------------------------------------------------------------------
     * @link https://openapi.wb.ru/promotion/api/ru/#tag/Stavki-mediakampanii
     */

    /**
     * Изменение ставки баннера
     *
     * Метод позволяет изменять ставку баннера в структуре items.
     * Изменение возможно только для кампаний в одном из статусов: 4, 5, 6, 9, 10, 11.
     * Допускается максимум 10 запросов в минуту.
     * @link https://openapi.wb.ru/promotion/api/ru/#tag/Stavki-mediakampanii/paths/~1adv~1v1~1item~1cpm~1change/post
     *
     * @param int $advertId ID медиакампании
     * @param int $itemId   ID баннера
     * @param int $cpm      Новая ставка
     *
     * @return bool
     */
    public function changeAdvertItemCpm(int $advertId, int $itemId, int $cpm): bool
    {
        $this->Adv->postRequest('/adv/v1/item/cpm/change', [
            'advert_id' => $advertId,
            'item_id' => $itemId,
            'cpm' => $cpm,
        ]);
        return $this->Adv->responseCode() == 200;
    }
}
