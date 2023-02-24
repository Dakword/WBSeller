<?php

namespace Dakword\WBSeller\Tests\Query;

use Dakword\WBSeller\Query\ErrorCardsList,
    Dakword\WBSeller\Tests\Query\TestCase,
    Dakword\WBSeller\Exception\ApiTimeRestrictionsException;


class ErrorCardsListTest extends TestCase
{

    public function test_Class()
    {
        $this->assertInstanceOf(ErrorCardsList::class, $this->Query()->ErrorCardsList());
    }

    public function test_ErrorCardsList()
    {
        try {
            $all = $this->Query()->ErrorCardsList()->getAll();
        } catch (ApiTimeRestrictionsException $exc) {
            $this->markTestSkipped($exc->getMessage());
        }
        
        $this->assertIsArray($all);
        if ($all) {
            $keys = array_keys($all);
            $allKeys = $this->Query()->ErrorCardsList()->find($keys);
            $this->assertEquals($keys, array_keys($allKeys));
            
            $oneKey = array_shift($keys);
            $oneError = $this->Query()->ErrorCardsList()->find($oneKey);
            $this->assertEquals($allKeys[$oneKey], $oneError);
        }
    }

}
