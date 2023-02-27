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
            $key1 = array_shift($keys);
            $key2 = array_shift($keys);
            $array = [$key1, $key2];
            
            $result = $this->Query()->ErrorCardsList()->find($array);
            $this->assertTrue(array_key_exists($key1, $result));
            $this->assertTrue(array_key_exists($key2, $result));
            
            $oneError = $this->Query()->ErrorCardsList()->find($key1);
            $this->assertEquals($all[$key1], $oneError);
        }
    }

}
