<?php

declare(strict_types=1);

namespace Dakword\WBSeller\API\Endpoint\Subpoint;

use Dakword\WBSeller\API\Endpoint\Adv;
use Dakword\WBSeller\Enum\AdvertType;
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
     * Управление зонами показов в автоматической кампании
     *
     * Метод позволяет изменять активность зон показов.
     * Допускается 1 запрос в секунду.
     * Вы можете осуществлять показы товаров во всех зонах либо выборочно.
     * @link https://openapi.wb.ru/promotion/api/ru/#tag/Upravlenie-parametrami-avtomaticheskih-kampanij/paths/~1adv~1v1~1auto~1active/post
     *
     * @param int  $id       Идентификатор кампании
     * @param bool $recom    Рекомендации на главной (false - отключены, true - включены)
     * @param bool $booster  Поиск/Каталог (false - отключены, true - включены)
     * @param bool $carousel Карточка товара (false - отключены, true - включены)
     *
     * @return bool
     */
    public function setAdvertActives(int $id, bool $recom, bool $booster, bool $carousel): bool
    {
        $this->Adv->postRequest('/adv/v1/auto/active?id=' . $id, [
            'recom' => $recom,
            'booster' => $booster,
            'carousel' => $carousel,
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

}
