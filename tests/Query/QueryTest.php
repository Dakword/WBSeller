<?php

namespace Dakword\WBSeller\Tests\Query;

use Dakword\WBSeller\Query;
use Dakword\WBSeller\Query\CardsList;
use Dakword\WBSeller\Query\ErrorCardsList;
use Dakword\WBSeller\Tests\Query\TestCase;

class QueryTest extends TestCase
{

    public function test_Class()
    {
        $this->assertInstanceOf(Query::class, $this->Query());
        $this->assertInstanceOf(CardsList::class, $this->Query()->CardsList());
        $this->assertInstanceOf(ErrorCardsList::class, $this->Query()->ErrorCardsList());
    }

}
