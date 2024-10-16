<?php

declare(strict_types=1);

namespace Dakword\WBSeller\API\Endpoint\Subpoint;

use Dakword\WBSeller\API\Endpoint\Adv;
use InvalidArgumentException;

class AdvSearchCatalog
{
    private Adv $Adv;

    public function __construct(Adv $Adv)
    {
        $this->Adv = $Adv;
    }

    /**
     * Создать кампанию Аукцион
     *
     * Максимум 5 запросов в минуту
     * @link https://openapi.wb.ru/promotion/api/ru/#tag/Prodvizhenie/paths/~1adv~1v2~1seacat~1save-ad/post
     *
     * @param string $name  Название кампании
     * @param array  $nmIds Номенклатуры для кампании
     *
     * @return int ID кампании
     *
     * @throws InvalidArgumentException Превышение максимального количества номенклатур в запросе
     */
    public function createAdvert(string $name, array $nmIds): int
    {
        $maxNms = 50;
        if (count($nmIds) > $maxNms) {
            throw new InvalidArgumentException("Превышение максимального количества номенклатур в запросе: {$maxNms}");
        }
        return $this->Adv->postRequest('/adv/v2/seacat/save-ad', [
            'campaignName' => $name,
            'nms' => $nmIds,
        ]);
    }

    /**
     * Изменение активности предметной группы
     *
     * Максимум 5 запросов в секунду.
     * @link https://openapi.wb.ru/promotion/api/ru/#tag/Upravlenie-parametrami-kampanij-v-poiske-i-poisk-+-katalog/paths/~1adv~1v0~1active/get
     * @deprecated since 17/10/2024
     *
     * @param int  $id        Идентификатор РК
     * @param int  $subjectId Идентификатор предметной группы, для которой меняется активность
     * @param bool $status    Новое состояние
     *                        true - сделать группу активной
     *                        false - сделать группу неактивной
     *
     * @return bool
     */
    public function setAdvertSubjectActive(int $id, int $subjectId, bool $status): bool
    {
        $this->Adv->getRequest('/adv/v0/active', [
            'id' => $id,
            'subjectId' => $subjectId,
            'status' => $status,
        ]);
        return $this->Adv->responseCode() == 200;
    }

    /**
     * Управление активностью фиксированных фраз
     *
     * Максимум 1 запрос в 500 миллисекунд.
     * @link https://openapi.wb.ru/promotion/api/ru/#tag/Upravlenie-parametrami-kampanij-v-poiske-i-poisk-+-katalog/paths/~1adv~1v1~1search~1set-plus/get
     *
     * @param int  $id     Идентификатор кампании
     * @param bool $active Новое состояние
     *                     true - сделать активными
     *                     false - сделать неактивными
     *
     * @return bool
     */
    public function setAdvertPlusesActive(int $id, bool $active): bool
    {
        $this->Adv->getRequest('/adv/v1/search/set-plus', [
            'id' => $id,
            'fixed' => $active,
        ]);
        return $this->Adv->responseCode() == 200;
    }

    /**
     * Установка фиксированных фраз
     *
     * Максимум 1 запрос в 500 миллисекунд.
     * @link https://openapi.wb.ru/promotion/api/ru/#tag/Upravlenie-parametrami-kampanij-v-poiske-i-poisk-+-katalog/paths/~1adv~1v1~1search~1set-plus/post
     *
     * @param int   $id     Идентификатор кампании
     * @param array $pluses Список фиксированных фраз
     *
     * @return array
     *
     * @throws InvalidArgumentException Превышение максимального количества фиксированных фраз в запросе
     */
    public function setAdvertPluses(int $id, array $pluses): array
    {
        $maxCount = 100;
        if (count($pluses) > $maxCount) {
            throw new InvalidArgumentException("Превышение максимального количества фиксированных фраз в запросе: {$maxCount}");
        }
        return $this->Adv->postRequest('/adv/v1/search/set-plus?id=' . $id, [
            'pluse' => $pluses,
        ]);
    }

    /**
     * Удаление фиксированных фраз
     *
     * Максимум 1 запрос в 500 миллисекунд.
     * @link https://openapi.wb.ru/promotion/api/ru/#tag/Upravlenie-parametrami-kampanij-v-poiske-i-poisk-+-katalog/paths/~1adv~1v1~1search~1set-plus/post
     *
     * @param int $id Идентификатор кампании
     *
     * @return array
     */
    public function deleteAdvertPluses(int $id): bool
    {
        return $this->setAdvertPluses($id, []);
    }

    /**
     * Установка минус-фраз фразового соответствия
     *
     * Максимально допустимое количество минус-фраз в кампании - 1000 шт.
     * Максимум 2 запроса в секунду.
     *
     * @param int   $id      Идентификатор кампании
     * @param array $phrases Минус-фразы фразового соответствия (макс. 1000 шт.)
     *
     * @return bool
     *
     * @throws InvalidArgumentException Превышение максимального количества минус-фраз в запросе
     */
    public function setAdvertMinusPhrases(int $id, array $phrases): bool
    {
        $maxCount = 1_000;
        if (count($phrases) > $maxCount) {
            throw new InvalidArgumentException("Превышение максимального количества минус-фраз в запросе: {$maxCount}");
        }
        $this->Adv->postRequest('/adv/v1/search/set-phrase?id=' . $id, [
            'phrase' => $phrases,
        ]);
        return $this->Adv->responseCode() == 200;
    }

    /**
     * Удаление минус-фраз фразового соответствия
     *
     * Максимум 2 запроса в секунду.
     *
     * @param int $id Идентификатор кампании
     *
     * @return bool
     */
    public function deleteAdvertMinusPhrases(int $id): bool
    {
        return $this->setAdvertMinusPhrases($id, []);
    }

    /**
     * Установка минус-фраз точного соответствия
     *
     * Максимально допустимое количество минус-фраз в кампании - 1000 шт.
     * Максимум 2 запроса в секунду.
     *
     * @param int   $id      Идентификатор кампании
     * @param array $strongs Минус-фразы точного соответствия(макс. 1000 шт.)
     *
     * @return bool
     *
     * @throws InvalidArgumentException Превышение максимального количества минус-фраз в запросе
     */
    public function setAdvertMinusStrong(int $id, array $strongs): bool
    {
        $maxCount = 1_000;
        if (count($strongs) > $maxCount) {
            throw new InvalidArgumentException("Превышение максимального количества минус-фраз в запросе: {$maxCount}");
        }
        $this->Adv->postRequest('/adv/v1/search/set-strong?id=' . $id, [
            'strong' => $strongs,
        ]);
        return $this->Adv->responseCode() == 200;
    }

    /**
     * Удаление минус-фраз точного соответствия
     *
     * Максимум 2 запроса в секунду.
     *
     * @param int $id Идентификатор кампании
     *
     * @return bool
     */
    public function deleteAdvertMinusStrong(int $id): bool
    {
        return $this->setAdvertMinusStrong($id, []);
    }

    /**
     * Установка минус-фраз из поиска
     *
     * Максимально допустимое количество минус-фраз в кампании - 1000 шт
     * Максимум 2 запроса в секунду.
     * @link https://openapi.wb.ru/promotion/api/ru/#tag/Upravlenie-parametrami-kampanij-v-poiske-i-poisk-+-katalog/paths/~1adv~1v1~1search~1set-excluded/post
     *
     * @param int   $id       Идентификатор кампании
     * @param array $excluded Минус-фразы (макс. 1000 шт.)
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
        $this->Adv->postRequest('/adv/v1/search/set-excluded?id=' . $id, [
            'excluded' => $excluded,
        ]);
        return $this->Adv->responseCode() == 200;
    }

    /**
     * Удаление минус-фраз из поиска
     *
     * Максимум 2 запроса в секунду.
     * @link https://openapi.wb.ru/promotion/api/ru/#tag/Upravlenie-parametrami-kampanij-v-poiske-i-poisk-+-katalog/paths/~1adv~1v1~1search~1set-excluded/post
     *
     * @param int $id Идентификатор кампании
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
     * Статистика поисковой кампании по ключевым фразам
     *
     * Метод позволяет получать статистику поисковой кампании по ключевым фразам.
     * Допускается максимум 4 запроса в секунду.
     * Информация обновляется примерно каждые полчаса.
     * @link https://openapi.wb.ru/promotion/api/ru/#tag/Statistika/paths/~1adv~1v1~1stat~1words/get
     *
     * @param int $id Идентификатор кампании
     *
     * @return object
     */
    public function advertStatisticByWords(int $id): object
    {
        return $this->Adv->getRequest('/adv/v1/stat/words', ['id' => $id]);
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
