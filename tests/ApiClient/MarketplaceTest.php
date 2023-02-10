<?php

namespace Dakword\WBSeller\Tests\ApiClient;

use Dakword\WBSeller\API\Endpoint\Marketplace;
use Dakword\WBSeller\Tests\ApiClient\TestCase;
use DateTime;
use InvalidArgumentException;

class MarketplaceTest extends TestCase
{

    public function test_Class()
    {
        $this->assertInstanceOf(Marketplace::class, $this->Marketplace());
    }

    public function test_getSuppliesList()
    {
        $result = $this->Marketplace()->getSuppliesList(500);
        $this->assertObjectHasAttribute('next', $result);
        $this->assertObjectHasAttribute('supplies', $result);

        $this->expectException(InvalidArgumentException::class);
        $this->Marketplace()->getSuppliesList(3000);
    }

    public function test_getSupply()
    {
        $result = $this->Marketplace()->getSupply('WB-GI-123456');
        $this->assertEquals($result->code, 'NotFound');

        $result1 = $this->Marketplace()->getSuppliesList();
        if($result1->supplies) {
            $supply = array_shift($result1->supplies);
            $id = $supply->id;
            $result2 = $this->Marketplace()->getSupply($id);
            $this->assertEquals($id, $result2->id);
        }
    }

    public function test_createSupply()
    {
        $this->expectException(InvalidArgumentException::class);
        $this->Marketplace()->createSupply(str_repeat('X', 200));
    }

    public function test_deleteSupply()
    {
        $result = $this->Marketplace()->deleteSupply('WB-GI-123456');
        $this->assertEquals($result->code, 'SupplyHasOrders');
    }

    public function test_getSupplyOrders()
    {
        $results = $this->Marketplace()->getSuppliesList();
        if ($results->supplies) {
            $supply = array_shift($results->supplies);
            $supplyId = $supply->id;
            $this->assertObjectHasAttribute('orders', $this->Marketplace()->getSupplyOrders($supplyId));
        } else {
            $this->markTestSkipped('No supplies in account');
        }
    }

    public function test_addSupplyOrder()
    {
        $result = $this->Marketplace()->addSupplyOrder('WB-GI-123456', 123456);
        $this->assertObjectHasAttribute('code', $result);
        $this->assertEquals($result->code, 'NotFound');
    }

    public function test_closeSupply()
    {
        $result = $this->Marketplace()->closeSupply('WB-GI-123456');
        $this->assertEquals($result->code, 'SupplyHasZeroOrders');
    }

    public function test_getReShipmentOrdersSupplies()
    {
        $result = $this->Marketplace()->getReShipmentOrdersSupplies();
        $this->assertObjectHasAttribute('orders', $result);
    }

    public function test_getSupplyBarcode()
    {
        $results = $this->Marketplace()->getSuppliesList();
        if ($results->supplies) {
            $supply = array_shift($results->supplies);
            $supplyId = $supply->id;
            $this->assertObjectHasAttribute('file', $this->Marketplace()->getSupplyBarcode($supplyId, 'svg', '40x30'));
            $this->assertObjectHasAttribute('file', $this->Marketplace()->getSupplyBarcode($supplyId, 'png', '58x40'));
        }

        $this->expectException(InvalidArgumentException::class);
        $this->Marketplace()->getSupplyBarcode('WB-GI-123456', 'jpg', '40x30');
        $this->Marketplace()->getSupplyBarcode('WB-GI-123456', 'png', '30x40');
    }
    
    public function test_confirmOrder()
    {
        $result = $this->Marketplace()->confirmOrder(123456);
        $this->assertEquals($result->code, 'NotFound');
    }

    public function test_deliverOrder()
    {
        $result = $this->Marketplace()->deliverOrder(123456);
        $this->assertEquals($result->code, 'NotFound');
    }

    public function test_receiveOrder()
    {
        $result = $this->Marketplace()->receiveOrder(123456);
        $this->assertEquals($result->code, 'NotFound');
    }

    public function test_rejectOrder()
    {
        $result = $this->Marketplace()->rejectOrder(123456);
        $this->assertEquals($result->code, 'NotFound');
    }

    public function test_cancelOrder()
    {
        $result = $this->Marketplace()->cancelOrder(123456);
        $this->assertEquals($result->code, 'NotFound');
    }

    public function test_gerOrdersStatuses()
    {
        $result1 = $this->Marketplace()->getOrders(10);
        if($result1) {
            $ids = array_column($result1->orders, 'id');
            $result2 = $this->Marketplace()->gerOrdersStatuses($ids);
            $this->assertEquals(count($ids), count($result2->orders));
        } else {
            $result2 = $this->Marketplace()->gerOrdersStatuses([]);
            $this->assertEquals($result2->code, 'IncorrectRequest');
        }
    }

    public function test_getOrders()
    {
        $result1 = $this->Marketplace()->getOrders(10);
        $this->assertObjectHasAttribute('orders', $result1);
        $this->assertObjectHasAttribute('next', $result1);

        $date = (new DateTime('2020-01-01'));
        $result2 = $this->Marketplace()->getOrders(20, 0, $date);
        $this->assertObjectHasAttribute('orders', $result2);
        $this->assertObjectHasAttribute('next', $result2);
        
        $this->expectException(InvalidArgumentException::class);
        $this->Marketplace()->getOrders(2000);
    }

    public function test_getNewOrders()
    {
        $result = $this->Marketplace()->getNewOrders();
        $this->assertObjectHasAttribute('orders', $result);
    }

    public function test_setOrderKiz()
    {
        $result = $this->Marketplace()->setOrderKiz(123456, []);
        $this->assertObjectHasAttribute('code', $result);
        $this->assertEquals($result->code, 'IncorrectRequest');
    }

    public function test_getOrdersStickers()
    {
        $result = $this->Marketplace()->getOrdersStickers([], 'svg', '40x30');
        $this->assertEquals($result->code, 'IncorrectRequest');

        $result = $this->Marketplace()->getOrdersStickers([123456], 'svg', '40x30');
        $this->assertObjectHasAttribute('stickers', $result);
        
        $this->expectException(InvalidArgumentException::class);
        $this->Marketplace()->getOrdersStickers([12345], 'foo', '40x30');
        $this->expectException(InvalidArgumentException::class);
        $this->Marketplace()->getOrdersStickers([12345], 'png', '30x50');
    }

    public function test_getWarehouses()
    {
        $result = $this->Marketplace()->getWarehouses();
        $this->assertIsArray($result);
    }

    public function test_updateWarehouseStocks()
    {
        $result = $this->Marketplace()->updateWarehouseStocks(123456, []);
        $this->assertEquals($result->code, 'IncorrectRequest');
    }

    public function test_deleteWarehouseStocks()
    {
        $result = $this->Marketplace()->deleteWarehouseStocks(123456, []);
        $this->assertEquals($result->code, 'IncorrectRequest');
    }

    public function test_getWarehouseStocks()
    {
        $wareHouses = $this->Marketplace()->getWarehouses();
        $id = $wareHouses ? $wareHouses[0]->id : 123456;
        $result = $this->Marketplace()->getWarehouseStocks($id, ['1234567890']);
        $this->assertObjectHasAttribute('stocks', $result);
    }

}
