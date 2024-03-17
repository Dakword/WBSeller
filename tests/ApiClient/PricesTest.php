<?php

namespace Dakword\WBSeller\Tests\ApiClient;

use Dakword\WBSeller\API\Endpoint\Prices;
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
     */
    public function test_getPrices()
    {
        $result = $this->Prices()->getPrices();
        $this->assertTrue(is_array($result->data->listGoods));
    }

    /**
     * @covers ::getNmIdPrice()
     */
    public function test_getNmIdPrice()
    {
        $result1 = $this->Prices()->getPrices();
        $item1 = array_shift($result1->data->listGoods);
        if ($item1) {
            $result2 = $this->Prices()->getNmIdPrice($item1->nmID);
            $this->assertTrue(is_array($result2->data->listGoods));
            $item2 = array_shift($result2->data->listGoods);
            $this->assertEquals($item1->nmID, $item2->nmID);
        } else {
            $this->markTestSkipped();
        }
    }

    /**
     * @covers ::getNmIdSizesPrices()
     */
    public function test_getNmIdSizesPrices()
    {
        $result = $this->Prices()->getNmIdSizesPrices(1234567);
        $this->assertObjectHasAttribute('listGoods', $result->data);
    }

    /**
     * @covers ::upload()
     */
    public function test_upload()
    {
        $result = $this->Prices()->upload([
            [
                'nmID' => 1234567,
                'price' => 1000,
                'discount' => 0,
            ]
        ]);

        $this->assertObjectHasAttribute('data', $result);
        $this->assertTrue($result->error);
    }

    /**
     * @covers ::getUploadStatus()
     */
    public function test_getUploadStatus()
    {
        $result = $this->Prices()->getUploadStatus(1234567);

        $this->assertObjectHasAttribute('data', $result);
        $this->assertNull($result->data);
    }

    /**
     * @covers ::getBufferUploadStatus()
     */
    public function test_getBufferUploadStatus()
    {
        $result = $this->Prices()->getBufferUploadStatus(1234567);

        $this->assertObjectHasAttribute('data', $result);
        $this->assertNull($result->data);
    }

    /**
     * @covers ::getUpload()
     */
    public function test_getUpload()
    {
        $result = $this->Prices()->getUpload(1234567);

        $this->assertObjectHasAttribute('data', $result);
        $this->assertNull($result->data->uploadID);
    }

}
