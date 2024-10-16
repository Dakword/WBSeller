<?php

declare(strict_types=1);

namespace Dakword\WBSeller\Enum;

/**
 * Варианты ответа продавца на заявку покупателя на возврат
 */
class ReturnAction
{
    /** @var string Одобрить с проверкой брака */
    const ACTION_APPROVE_CHECK = 'approve1';

    /** @var string Одобрить и забрать товар */
    const ACTION_APPROVE_RETURN = 'approve2';

    /** @var string Одобрить без возврата товара */
    const ACTION_APPROVE_NORETURN = 'autorefund1';

    /** @var string Отклонить - Брак не обнаружен */
    const ACTION_REJECT_NODEFECT = 'reject1';

    /** @var string Отклонить - Добавить фото/видео */
    const ACTION_REJECT_ADDMEDIA = 'reject2';

    /** @var string Отклонить - Направить в сервисный центр */
    const ACTION_REJECT_SERVICE = 'reject3';

    /** @var string Отклонить с комментарием */
    const ACTION_REJECT_CUSTOM = 'rejectcustom';

    public static function all(): array
    {
        return [
            self::ACTION_APPROVE_CHECK,
            self::ACTION_APPROVE_RETURN,
            self::ACTION_APPROVE_NORETURN,
            self::ACTION_REJECT_NODEFECT,
            self::ACTION_REJECT_ADDMEDIA,
            self::ACTION_REJECT_SERVICE,
            self::ACTION_REJECT_CUSTOM,
        ];
    }

}
