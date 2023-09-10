<?php

namespace Dakword\WBSeller\Tests\ApiClient;

use Dakword\WBSeller\API\Endpoint\Marketplace,
    Dakword\WBSeller\Exception\ApiTimeRestrictionsException,
    Dakword\WBSeller\Tests\ApiClient\TestCase;
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
        $this->assertEquals($result->code, 'InternalServerError');
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
            $this->assertObjectHasAttribute('file', $this->Marketplace()->getSupplyBarcode($supplyId, 'svg'));
            $this->assertObjectHasAttribute('file', $this->Marketplace()->getSupplyBarcode($supplyId, 'png'));
        }

        $this->expectException(InvalidArgumentException::class);
        $this->Marketplace()->getSupplyBarcode('WB-GI-123456', 'jpg');
        $this->Marketplace()->getSupplyBarcode('WB-GI-123456', 'png');
    }
    
    public function test_cancelOrder()
    {
        $result = $this->Marketplace()->cancelOrder(123456);
        $this->assertEquals($result->code, 'NotFound');
    }

    public function test_gerOrdersStatuses()
    {
        $result1 = $this->Marketplace()->getOrders(10);
        if($result1->orders) {
            $ids = array_column($result1->orders, 'id');
            $result2 = $this->Marketplace()->getOrdersStatuses($ids);
            $this->assertEquals(count($ids), count($result2->orders));
        } else {
            $result2 = $this->Marketplace()->getOrdersStatuses([]);
            $this->assertEquals($result2->code, 'IncorrectRequest');
        }
    }

    public function test_getOrders()
    {
        $result1 = $this->Marketplace()->getOrders(10);
        $this->assertObjectHasAttribute('orders', $result1);

        $date = (new DateTime('2020-01-01'));
        $result2 = $this->Marketplace()->getOrders(20, 0, $date);
        $this->assertObjectHasAttribute('orders', $result2);
        
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

    public function test_setOrderUin()
    {
        $result = $this->Marketplace()->setOrderUin(123456, '1234567890123456');
        $this->assertFalse($result);
    }

    public function test_setOrderIMEI()
    {
        $result = $this->Marketplace()->setOrderIMEI(123456, '123456789012345');
        $this->assertFalse($result);
    }

    public function test_setOrderGTIN()
    {
        $result = $this->Marketplace()->setOrderGTIN(123456, '1234567890123');
        $this->assertFalse($result);
    }

    public function test_getOrderMeta()
    {
        $result = $this->Marketplace()->getOrderMeta(123456);
        $this->assertObjectHasAttribute('code', $result);
        $this->assertEquals($result->code, 'NotFound');
    }

    public function test_deleteOrderMeta()
    {
        $result = $this->Marketplace()->deleteOrderMeta(123456, 'uin');
        $this->assertFalse($result);

        $this->expectException(InvalidArgumentException::class);
        $result = $this->Marketplace()->deleteOrderMeta(123456, 'ean');
    }

    public function test_getOrdersExternalStickers()
    {
        $result = $this->Marketplace()->getOrdersExternalStickers([123456]);
        $this->assertObjectHasAttribute('stickers', $result);
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
        $wareHouses = $this->Marketplace()->Warehouses()->list();
        $id = $wareHouses ? $wareHouses[0]->id : 123456;
        $result = $this->Marketplace()->getWarehouseStocks($id, ['1234567890']);
        $this->assertObjectHasAttribute('stocks', $result);
    }

    public function test_getSupplyBoxes()
    {
        $result = $this->Marketplace()->getSupplyBoxes('WB-GI-1234567');
        $this->assertObjectHasAttribute('trbxes', $result);
    }

    public function test_addSupplyBoxes()
    {
        $result = $this->Marketplace()->addSupplyBoxes('WB-GI-1234567', 5);
        $this->assertEquals($result->code, 'FailedToAddSupplyTrbx');
    }

    public function test_deleteSupplyBoxes()
    {
        $API = $this->Marketplace();
        $result = $API->deleteSupplyBoxes('WB-GI-0123456', ['WB-TRBX-0123456']);
        $this->assertTrue($result);
    }

    public function test_addBoxOrders()
    {
        $API = $this->Marketplace();
        $result = $API->addBoxOrders('WB-GI-0123456', 'WB-TRBX-0123456', [123456]);
        $this->assertTrue($result);
    }

    public function test_deleteBoxOrder()
    {
        $API = $this->Marketplace();
        $result = $API->deleteBoxOrder('WB-GI-0123456', 'WB-TRBX-0123456', 123456);
        $this->assertTrue($result);
    }

    public function test_getSupplyBoxStickers()
    {
        $API = $this->Marketplace();
        $result = $API->getSupplyBoxStickers('WB-GI-0123456', ['WB-TRBX-0123456']);
        $this->assertEquals($result->code, 'NotFound');
    }

}
