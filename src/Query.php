<?php
declare(strict_types=1);

namespace Dakword\WBSeller;

use Dakword\WBSeller\API;
use Dakword\WBSeller\Query\CardsList;
use Dakword\WBSeller\Query\ErrorCardsList;

class Query
{

    private API $API;
        
    function __construct(API $API)
    {
        $this->API = $API;
    }

    /**
     * Список НМ
     * 
     * @return CardsList
     */
    public function CardsList(): CardsList
    {
        return new CardsList($this->API);
    }

    /**
     * Список ошибочных НМ
     * 
     * @return ErrorCardsList
     */
    public function ErrorCardsList(): ErrorCardsList
    {
        return new ErrorCardsList($this->API);
    }

}
