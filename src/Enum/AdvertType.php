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
     * @deprecated устаревший тип
     */
    const ON_CATALOG = 4;

    /**
     * @var int Реклама в карточке товара
     * @deprecated устаревший тип
     */
    const ON_CARD = 5;

    /**
     * @var int Реклама в поиске
     * @deprecated устаревший тип
     */
    const ON_SEARCH = 6;

    /**
     * @var int Реклама в рекомендациях на главной странице
     * @deprecated устаревший тип
     */
    const ON_HOME_RECOM = 7;

    /**
     * @var int Автоматическая
     */
    const AUTO = 8;

    /**
     * @var int Реклама в поиске и каталоге
     */
    const ON_SEARCH_CATALOG = 9;

    public static function all(): array
    {
        return [
            self::ON_CATALOG,
            self::ON_CARD,
            self::ON_SEARCH,
            self::ON_HOME_RECOM,
            self::AUTO,
            self::ON_SEARCH_CATALOG,
        ];
    }

}
