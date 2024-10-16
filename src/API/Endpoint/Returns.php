<?php

declare(strict_types=1);

namespace Dakword\WBSeller\API\Endpoint;

use Dakword\WBSeller\API\AbstractEndpoint;
use Dakword\WBSeller\Enum\ReturnAction;
use InvalidArgumentException;

class Returns extends AbstractEndpoint
{

    /**
     * Заявки покупателей на возврат
     *
     * Возвращает заявки покупателей на возврат товаров за текущие 14 дней.
     *
     * @param bool  $archived Состояние заявки: true - в архиве, false - на рассмотрении
     * @param int   $page     Номер страницы
     * @param array $filter   Возможные ключи массива: id - UUID заявки, nmId - артикул WB
     * @param int   $limit    Количество заявок на странице
     *
     * @return object {claims: [], total: int}
     *
     * @throws InvalidArgumentException Превышение максимального значения параметра limit
     */
    public function list(bool $archived, int $page = 1, array $filter = [], int $limit = 200): object
    {
        $maxLimit = 200;
        if ($limit >  $maxLimit) {
            throw new InvalidArgumentException("Превышение максимального значения параметра limit: {$maxLimit}");
        }
        return $this->getRequest('/api/v1/claims', [
            'is_archive' => $archived,
            'limit' => $limit,
            'offset' => --$page * $limit,
        ] + (isset($filter['id']) ? [
            'id' => $filter['id']
        ] : []) + (isset($filter['nmId']) ? [
            'nmId' => $filter['nmId']
        ] : []));
    }

    /**
     * Ответ на заявку покупателя
     *
     * @param string $id      UUID заявки
     * @param string $action  Действие с заявкой Enum\ReturnAction::class
     * @param string $comment Комментарий при Enum\ReturnAction::ACTION_REJECT_CUSTOM
     *
     * @return bool true - успешно
     *
     * @throws InvalidArgumentException Неизвестный ответ на заявку
     */
    public function action(string $id, string $action, string $comment = ''): bool
    {
        if (!in_array($action, ReturnAction::all())) {
            throw new InvalidArgumentException('Неизвестный ответ на заявку: ' . $action);
        }
        $this->patchRequest('/api/v1/claim', [
            'id' => $id,
            'action' => $action,
        ] + ($action == ReturnAction::ACTION_REJECT_CUSTOM ? [
            'comment' => $comment,
        ] : []));
        return $this->responseCode() == 200;
    }

}
