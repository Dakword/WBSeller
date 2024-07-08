<?php

declare(strict_types=1);

namespace Dakword\WBSeller\Enum;

/**
 * Статус рекламной кампании
 */
class AdvertStatus
{
    /**
     * @var int Рекламная кампания в процессе удаления
     */
    const DELETED = -1;

    /**
     * @var int Рекламная кампания готова к запуску
     */
    const READY = 4;

    /**
     * @var int Рекламная кампания завершена
     */
    const DONE = 7;

    /**
     * @var int отказался
     */
    const CANCELLED = 8;

    /**
     * @var int Идут показы
     */
    const PLAY = 9;

    /**
     * @var int Рекламная кампания на паузе
     */
    const PAUSE = 11;

    public static function all(): array
    {
        return [
            self::DELETED,
            self::READY,
            self::DONE,
            self::CANCELLED,
            self::PLAY,
            self::PAUSE,
        ];
    }

}
