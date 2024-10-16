<?php

declare(strict_types=1);

namespace Dakword\WBSeller\API\Endpoint\Subpoint;

use Dakword\WBSeller\API\Endpoint\Adv;
use Dakword\WBSeller\Enum\AdvertType;
use DateTime;
use InvalidArgumentException;

class AdvAuto
{
    private Adv $Adv;

    public function __construct(Adv $Adv)
    {
        $this->Adv = $Adv;
    }

    /**
     * Создать автоматическую кампанию
     *
     * Максимум 1 запрос в 20 секунд.
     * @link https://openapi.wb.ru/promotion/api/ru/#tag/Prodvizhenie/paths/~1adv~1v1~1save-ad/post
     *
     * @param string $name      Название кампании
     * @param int    $subjectId ID предмета, для которого создается кампания
     * @param int    $summa     Сумма пополнения
     * @param int    $btype     Tип списания: 0 - Счёт, 1 - Баланс, 3 - Бонусы
     * @param int    $cpm       Ставка
     * @param array  $nmIds     Массив артикулов WB
     * @param bool   $on_pause  После создания кампания: true - будет на паузе
     *                                                   Запуск кампании будет доступен через 3 минуты после создания кампании.
     *                                                   false - будет сразу запущена
     *
     * @return string ID созданной кампании
     *
     * @throws InvalidArgumentException Превышение максимального количества номенклатур в запросе
     * @throws InvalidArgumentException Неизвестный тип списания
     */
    public function createAdvert(string $name, int $subjectId, int $summa, int $btype, int $cpm, array $nmIds, bool $on_pause = true): string
    {
        $maxNms = 100;
        if (count($nmIds) > $maxNms) {
            throw new InvalidArgumentException("Превышение максимального количества номенклатур в запросе: {$maxNms}");
        }
        if (!in_array($btype, [0, 1, 3])) {
            throw new InvalidArgumentException("Неизвестный тип списания: {$btype}");
        }
        return $this->Adv->postRequest('/adv/v1/save-ad', [
            'type' => AdvertType::AUTO,
            'name' => mb_substr($name, 0, 128),
            'subjectId' => $subjectId,
            'sum' => $summa,
            'btype' => $btype,
            'cpm' => $cpm,
            'nms' => $nmIds,
            'on_pause' => $on_pause,
        ]);
    }

    /**
     * Список номенклатур для автоматической кампании
     *
     * Метод позволяет получать список номенклатур, доступных для добавления в кампанию.
     * Допускается 1 запрос в секунду.
     * @link https://openapi.wb.ru/promotion/api/ru/#tag/Upravlenie-parametrami-avtomaticheskih-kampanij/paths/~1adv~1v1~1auto~1getnmtoadd/get
     *
     * @param int $id Идентификатор кампании
     *
     * @return array Список доступных номенклатур
     */
    public function getAdvertNmsToAdd(int $id): array
    {
        return $this->Adv->getRequest('/adv/v1/auto/getnmtoadd', ['id' => $id]);
    }

    /**
     * Изменение списка номенклатур в автоматической кампании
     *
     * Метод позволяет добавлять и удалять номенклатуры.
     * Допускается 1 запрос в секунду.
     * Важно: Добавить можно только те номенклатуры, которые вернутся в ответе метода
     * "Список номенклатур для автоматической кампании" (getAdvertNmsToAdd)
     * Удалить единственную номенклатуру из кампании нельзя.
     * Проверки по параметру delete не предусмотрено.
     * Если пришел ответ со статус-кодом 200, а изменений не произошло,
     * то проверьте запрос на соответствие документации.
     *
     * @param int   $id          Идентификатор кампании
     * @param array $nmsToAdd    Номенклатуры, которые необходимо добавить
     * @param array $nmsToDelete Номенклатуры, которые необходимо удалить
     *
     * @return bool
     */
    public function updateAdvertNms(int $id, array $nmsToAdd, array $nmsToDelete): bool
    {
        $this->Adv->getRequest('/adv/v1/auto/updatenm?id=' . $id, [
            'add' => $nmsToAdd,
            'delete' => $nmsToDelete,
        ]);
        return $this->Adv->responseCode() == 200;
    }

    /**
     * Установка минус-фраз для автоматической кампании
     *
     * Допускается 1 запрос в 6 секунд.
     * @link https://openapi.wb.ru/promotion/api/ru/#tag/Upravlenie-parametrami-avtomaticheskih-kampanij/paths/~1adv~1v1~1auto~1set-excluded/post
     *
     * @param int   $id       Идентификатор кампании
     * @param array $excluded Список фраз (макс. 1000 шт.)
     *
     * @return bool
     *
     * @throws InvalidArgumentException Превышение максимального количества минус-фраз в запросе
     */
    public function setAdvertMinuses(int $id, array $excluded): bool
    {
        $maxCount = 1_000;
        if (count($excluded) > $maxCount) {
            throw new InvalidArgumentException("Превышение максимального количества минус-фраз в запросе: {$maxCount}");
        }
        $this->Adv->postRequest('/adv/v1/auto/set-excluded?id=' . $id, [
            'excluded' => $excluded,
        ]);
        return $this->Adv->responseCode() == 200;
    }

    /**
     * Удаление минус-фраз для автоматической кампании
     *
     * Допускается 1 запрос в 6 секунд.
     * @link https://openapi.wb.ru/promotion/api/ru/#tag/Upravlenie-parametrami-avtomaticheskih-kampanij/paths/~1adv~1v1~1auto~1set-excluded/post
     *
     * @param int   $id       Идентификатор кампании
     *
     * @return bool
     */
    public function deleteAdvertMinuses(int $id): bool
    {
        return $this->setAdvertMinuses($id, []);
    }

    /*
     * СТАТИСТИКА
     * --------------------------------------------------------------------------
     * @link https://openapi.wb.ru/promotion/api/ru/#tag/Statistika
     */

    /**
     * Статистика автоматической кампании
     *
     * Метод позволяет получать краткую статистику по автоматической кампании.
     * Допускается 1 запрос в 6 секунд.
     * @link https://openapi.wb.ru/promotion/api/ru/#tag/Statistika/paths/~1adv~1v1~1auto~1stat/get
     *
     * @param int $id Идентификатор кампании
     *
     * @return object
     */
    public function advertStatistic(int $id): object
    {
        return $this->Adv->getRequest('/adv/v1/auto/stat', ['id' => $id]);
    }

    /**
     * Статистика автоматической кампании по кластерам фраз
     *
     * Возвращает кластеры ключевых фраз (наборы похожих), по которым показывались товары в кампании,
     * и количество показов по ним. В ответ метода попадают только те фразы,
     * по которым товары показывались хотя бы один раз.
     * Информация обновляется раз в 15 минут.
     * Максимум — 4 запроса секунду.
     * @link https://openapi.wb.ru/promotion/api/ru/#tag/Statistika/paths/~1adv~1v2~1auto~1stat-words/get
     *
     * @param int $id Идентификатор кампании
     *
     * @return object
     */
    public function advertStatisticByWords(int $id): object
    {
        return $this->Adv->getRequest('/adv/v1/auto/stat-words', ['id' => $id]);
    }

    /**
     * Статистика по ключевым фразам
     *
     * Возвращает статистику по ключевым фразам за каждый день, когда кампания была активна.
     * За один запрос можно получить данные максимум за 7 дней.
     * Информация обновляется раз в час.
     * Максимум 4 запроса секунду
     * @link https://openapi.wb.ru/promotion/api/ru/#tag/Statistika/paths/~1adv~1v0~1stats~1keywords/get
     *
     * @param int      $id       Идентификатор кампании
     * @param DateTime $dateFrom Начало периода
     * @param DateTime $dateTo   Конец периода
     *
     * @return array
     */
    public function advertStatisticByKeywords(int $id, DateTime $dateFrom, DateTime $dateTo): array
    {
        return $this->Adv->advertStatisticByKeywords($id, $dateFrom, $dateTo);
    }
}
