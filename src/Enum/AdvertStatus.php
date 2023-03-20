<?php

declare(strict_types=1);

namespace Dakword\WBSeller\Enum;

/**
 * Статус рекламной кампании
 */
class AdvertStatus
{
    /**
     * @var int Рекламная кампаниязавершена
     */
    const DONE = 7;

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
            self::PLAY,
            self::PAUSE,
        ];
    }

}
