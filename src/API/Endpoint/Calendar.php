<?php

declare(strict_types=1);

namespace Dakword\WBSeller\API\Endpoint;

use Dakword\WBSeller\API\AbstractEndpoint;
use DateTime;
use InvalidArgumentException;

/**
 * Календарь акций
 * С помощью этих методов можно получать информацию об акциях и принимать в них участие.
 * Максимум 10 запросов за 6 секунд суммарно для всех методов
 */
class Calendar extends AbstractEndpoint
{
    /**
     * Список акций
     *
     * Возвращает список акций с датами и временем проведения
     *
     * @param DateTime $fromDate Начало периода
     * @param DateTime $toDate   Конец периода
     * @param bool     $allPromo Показать все акции: true.
     *                           Показать доступные для участия акции: false
     * @param int      $page     Номер страницы
     * @param int      $limit    Количество акций на странице
     *
     * @throws InvalidArgumentException Превышение максимального количества запрошенных акций
     */
    public function promotions(DateTime $fromDate, DateTime $toDate, bool $allPromo = false, int $page = 1, int $limit = 1_000)
    {
        $maxLimit = 1_000;
        if ($limit > $maxLimit) {
            throw new InvalidArgumentException("Превышение максимального количества запрошенных акций: {$maxLimit}");
        }
        return $this->getRequest('/api/v1/calendar/promotions', [
            'startDateTime' => $fromDate->format(DATE_RFC3339),
            'endDateTime' => $toDate->format(DATE_RFC3339),
            'allPromo' => $allPromo,
            'limit' => $limit,
            'offset' => --$page * $limit,
        ]);
    }

    /**
     * Детальная информация по акциям
     *
     * @link https://openapi.wb.ru/prices/api/ru/#tag/Kalendar-akcij/paths/~1api~1v1~1calendar~1promotions~1details/get
     *
     * @param array $promotionIds ID акций
     */
    public function promotionsDetails(array $promotionIds)
    {
        return $this->getRequest('/api/v1/calendar/promotions/details', [
            'promotionIDs' => $promotionIds,
        ]);
    }

    /**
     * Список товаров для участия в акции
     *
     * Возвращает список товаров, подходящих для участия в акции.
     * Неприменимо для автоакций
     * @link https://openapi.wb.ru/prices/api/ru/#tag/Kalendar-akcij/paths/~1api~1v1~1calendar~1promotions~1nomenclatures/get
     *
     * @param int  $promotionId ID акции
     * @param bool $inAction    Участвует в акции: true - да, false - нет
     * @param int  $page        Номер страницы
     * @param int  $limit       Количество товаров в ответе
     *
     * @throws InvalidArgumentException Превышение максимального количества запрошенных акций
     */
    public function promotionNomenclatures(int $promotionId, bool $inAction = false, int $page = 1, int $limit = 1_000)
    {
        $maxLimit = 1_000;
        if ($limit > $maxLimit) {
            throw new InvalidArgumentException("Превышение максимального количества запрошенных акций: {$maxLimit}");
        }
        return $this->getRequest('/api/v1/calendar/promotions/nomenclatures', [
            'promotionID' => $promotionId,
            'inAction' => $inAction,
            'limit' => $limit,
            'offset' => --$page * $limit,
        ]);
    }

    /**
     * Добавить товар в акцию
     *
     * Создаёт загрузку товара в акцию.
     * Состояние загрузки можно проверить с помощью отдельных методов
     * (https://openapi.wb.ru/prices/api/ru/#tag/Sostoyaniya-zagruzok).
     * Неприменимо для автоакций
     * @link https://openapi.wb.ru/prices/api/ru/#tag/Kalendar-akcij/paths/~1api~1v1~1calendar~1promotions~1upload/post
     *
     * @param int   $promotionId   ID акции
     * @param array $nomenclatures ID номенклатур, которые можно добавить в акцию
     * @param bool  $uploadNow     Установить скидку: true - сейчас
     *                                                false - в момент старта акции
     *
     * @throws InvalidArgumentException Превышение максимального количества переданных номенклатур
     */
    public function promotionUpload(int $promotionId, array $nomenclatures, bool $uploadNow = false)
    {
        $maxCount = 1_000;
        if (count($nomenclatures) > $maxCount) {
            throw new InvalidArgumentException("Превышение максимального количества переданных номенклатур: {$maxCount}");
        }
        return $this->postRequest('/api/v1/calendar/promotions/upload', [
            'promotionID' => $promotionId,
            'uploadNow' => $uploadNow,
            'nomenclatures' => $nomenclatures,
        ]);
    }
}
