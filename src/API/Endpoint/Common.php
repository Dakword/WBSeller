<?php

declare(strict_types=1);

namespace Dakword\WBSeller\API\Endpoint;

use Dakword\WBSeller\API\AbstractEndpoint;
use Dakword\WBSeller\API\Endpoint\Subpoint\News;

class Common extends AbstractEndpoint
{

    /**
     * Сервис для получения новостей с портала продавцов.
     */
    public function News(): News
    {
        return new News($this);
    }

    /**
     * Получение информации о продавце
     *
     * Метод позволяет получать наименование продавца и ID его аккаунта.
     * В запросе можно использовать любой токен, у которого не выбрана опция Тестовый контур.
     * Максимум 1 запрос в минуту на один аккаунт продавца
     * @link https://dev.wildberries.ru/openapi/api-information/#tag/Informaciya-o-prodavce/paths/~1api~1v1~1seller-info/get
     */
    public function sellerInfo()
    {
        return $this->getRequest('/api/v1/seller-info');

    }
}
