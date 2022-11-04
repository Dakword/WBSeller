<?php

declare(strict_types=1);

namespace Dakword\WBSeller\Enums;

class SupplyStatus
{
    /**
     * @var string Активная поставка
     */
    const ACTIVE = 'ACTIVE';

    /**
     * @var string Поставка в пути (не принята на складе)
     */
    const ON_DELIVERY = 'ON_DELIVERY';
}
