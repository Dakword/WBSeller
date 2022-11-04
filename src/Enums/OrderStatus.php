<?php

declare(strict_types=1);

namespace Dakword\WBSeller\Enums;

class OrderStatus
{
    /**
     * @var int НОВЫЙ
     */
    const NEW = 0;

    /**
     * @var int В РАБОТЕ
     */
    const INWORK = 1;

    /**
     * @var int ЗАВЕРШЕН
     */
    const COMPLETED = 2;

    /**
     * @var int ОТКЛОНЕН ПОСТАВЩИКОМ
     */
    const REJECTED = 3;

    /**
     * @var int ДОСТАВЛЯЕТСЯ
     */
    const DELIVERY = 5;

    /**
     * @var int ПОЛУЧЕН
     */
    const ACCEPTED = 6;

    /**
     * @var int НЕ ПРИНЯТ КЛИЕНТОМ
     */
    const NOTACCEPTED = 7;

    /**
     * @var int ГОТОВИТСЯ К ВЫДАЧЕ
     */
    const PREPARING = 8;

    /**
     * @var int ГОТОВ К ВЫДАЧЕ
     */
    const READY = 9;

    public static function allowedStatuses(): array
    {
        return [
            self::NEW,
            self::INWORK,
            self::COMPLETED,
            self::REJECTED,
            self::DELIVERY,
            self::ACCEPTED,
            self::NOTACCEPTED,
            self::PREPARING,
            self::READY,
        ];
    }

}
