<?php

declare(strict_types=1);

namespace Dakword\WBSeller\API\Endpoint;

use Dakword\WBSeller\API\AbstractEndpoint;
use Dakword\WBSeller\API\Endpoint\Subpoint\News;

class Common extends AbstractEndpoint
{

    /**
     * Сервис для получения новостей с портала продавцов.
     *
     * @return News
     */
    public function News(): News
    {
        return new News($this);
    }

}
