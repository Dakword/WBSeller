<?php

declare(strict_types=1);

namespace Dakword\WBSeller\Enum;

/**
 * Тип рекламной кампании
 */
class AdvertType
{
    /**
     * @var int Реклама в каталоге
     */
    const ON_CATALOG = 4;

    /**
     * @var int Реклама в карточке товара
     */
    const ON_CARD = 5;

    /**
     * @var int Реклама в поиске
     */
    const ON_SEARCH = 6;

    /**
     * @var int Реклама в рекомендациях на главной странице
     */
    const ON_HOME_RECOM = 7;

    public static function all(): array
    {
        return [
            self::ON_CATALOG,
            self::ON_CARD,
            self::ON_SEARCH,
            self::ON_HOME_RECOM,
        ];
    }

}
