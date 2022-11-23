<?php

namespace Dakword\WBSeller\Tests\ApiClient;

use Dakword\WBSeller\API\Endpoint\Promo;
use Dakword\WBSeller\Tests\ApiClient\TestCase;

class PromoTest extends TestCase
{

    public function test_Class()
    {
        $this->assertInstanceOf(Promo::class, $this->API()->Promo());
    }

    public function test_updateDiscounts()
    {
        $result = $this->API()->Promo()->updateDiscounts([], new \DateTime('2050-01-01'));
        if(property_exists($result, 'errors')) {
            $this->assertTrue(in_array('требуемая колонка не заполнена', $result->errors));
        }
    }

}
