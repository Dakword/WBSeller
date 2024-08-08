<?php

declare(strict_types=1);

namespace Dakword\WBSeller\Enum;

/**
 * Тип источника пополнения бюджета рекламной кампании
 */
class AdvertDepositSource
{
    /**
     * @var int Счет
     */
    const ACCOUNT = 0;

    /**
     * @var int Баланс
     */
    const BALANCE = 1;

    /**
     * @var int Бонусы
     */
    const BONUSES = 2;

    public static function all(): array
    {
        return [
            self::ACCOUNT,
            self::BALANCE,
            self::BONUSES,
        ];
    }
}
