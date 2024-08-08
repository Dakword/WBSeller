<?php

declare(strict_types=1);

namespace Dakword\WBSeller\Enum;

/**
 * Статус медиакампании
 */
class MediaAdvertStatus
{
    /**
     * @var int Черновик
     */
    const DRAFT = 1;

    /**
     * @var int Модерация
     */
    const MODERATION = 2;

    /**
     * @var int Отклонено (с возможностью вернуть на модерацию)
     */
    const REJECTED = 3;

    /**
     * @var int Одобрено
     */
    const ACCEPTED = 4;

    /**
     * @var int Запланировано
     */
    const PLANNED = 5;

    /**
     * @var int Идут показы
     */
    const PLAYED = 6;

    /**
     * @var int Завершено
     */
    const COMPLETED = 7;

    /**
     * @var int Отказался
     */
    const CANCELLED = 8;

    /**
     * @var int Приостановлено продавцом
     */
    const PAUSED = 9;

    /**
     * @var int Пауза по дневному лимиту
     */
    const PAUSED_BY_LIMIT = 10;

    /**
     * @var int Пауза по расходу бюджета
     */
    const PAUSED_BY_BUDGET = 11;

    public static function all(): array
    {
        return [
            self::DRAFT,
            self::MODERATION,
            self::REJECTED,
            self::ACCEPTED,
            self::PLANNED,
            self::PLAYED,
            self::COMPLETED,
            self::CANCELLED,
            self::PAUSED,
            self::PAUSED_BY_LIMIT,
            self::PAUSED_BY_BUDGET,
        ];
    }

}
