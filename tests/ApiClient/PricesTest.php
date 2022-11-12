<?php

namespace Dakword\WBSeller\Tests\ApiClient;

use Dakword\WBSeller\API\Endpoints\Prices;
use Dakword\WBSeller\Tests\ApiClient\TestCase;

/**
 * @coversDefaultClass \Dakword\WBSeller\Endpoints\Prices
 */
class PricesTest extends TestCase
{

    public function test_Class()
    {
        $this->assertInstanceOf(Prices::class, $this->API()->Prices());
    }

    /**
     * @covers ::getPrices()
     * @covers ::getPricesOnStock()
     * @covers ::getPricesNoStock()
     */
    public function test_getInfo()
    {
        $result1 = $this->Prices()->getPrices();
        $this->assertTrue(is_array($result1));
        $count_0 = count($result1);

        $result2 = $this->Prices()->getPricesOnStock();
        $this->assertTrue(is_array($result2));
        $count_1 = count($result2);

        $result3 = $this->Prices()->getPricesNoStock();
        $this->assertTrue(is_array($result3));
        $count_2 = count($result3);

        $this->assertEquals($count_0, $count_1 + $count_2);
    }

    /**
     * @covers ::updatePrices()
     */
    public function test_updatePrices()
    {
        $result1 = $this->Prices()->getPricesNoStock();
        $item = array_shift($result1);
        if ($item) {
            $result2 = $this->Prices()->updatePrices([
                [
                    'nmId' => $item->nmId,
                    'price' => $item->price,
                ]
            ]);
            if (property_exists($result2, 'errors')) {
                // errors: "все номенклатуры с ценами из списка уже загружены, новая загрузка не создана"
                $this->assertObjectHasAttribute('errors', $result2);
            } else {
                $this->assertObjectHasAttribute('uploadId', $result2);
            }
        } else {
            $this->markTestSkipped();
        }
    }

}
