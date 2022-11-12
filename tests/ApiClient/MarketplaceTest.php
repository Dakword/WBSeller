<?php

namespace Dakword\WBSeller\Tests\ApiClient;

use Dakword\WBSeller\API\Endpoints\Marketplace;
use Dakword\WBSeller\Enums\OrderStatus;
use Dakword\WBSeller\Enums\SupplyStatus;
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
        $result = $this->Marketplace()->getSuppliesList('ACTIVE');
        $this->assertObjectHasAttribute('supplies', $result);

        $result = $this->Marketplace()->getSuppliesList(SupplyStatus::ON_DELIVERY);
        $this->assertObjectHasAttribute('supplies', $result);

        $this->expectException(InvalidArgumentException::class);
        $result = $this->Marketplace()->getSuppliesList('activ');
    }

    public function test_addSupplyOrder()
    {
        $result = $this->Marketplace()->addSupplyOrder('WB-GI-123456', ['12345']);
        $this->assertObjectHasAttribute('error', $result);
    }

    public function test_closeSupply()
    {
        $result = $this->Marketplace()->closeSupply('WB-GI-123456');
        $this->assertEquals('Поставка не найдена', $result->errorText);
    }

    public function test_getSupplyBarcode()
    {
        $results = $this->Marketplace()->getSuppliesList(SupplyStatus::ON_DELIVERY);

        if ($results->supplies) {
            $supply = array_shift($results->supplies);
            $supplyId = $supply->supplyId;
            $this->assertObjectHasAttribute('file', $this->Marketplace()->getSupplyBarcode($supplyId, 'svg'));
            $this->assertObjectHasAttribute('file', $this->Marketplace()->getSupplyBarcode($supplyId, 'pdf'));
        }

        $this->expectException(InvalidArgumentException::class);
        $this->Marketplace()->getSupplyBarcode('WB-GI-7693796', 'jpg');
    }

    public function test_getSupplyOrders()
    {
        $result = $this->Marketplace()->getSupplyOrders('WB-GI-123456');
        $this->assertEquals('Поставка не найдена', $result->errorText);

        $results = $this->Marketplace()->getSuppliesList(SupplyStatus::ON_DELIVERY);
        if ($results->supplies) {
            $supply = array_shift($results->supplies);
            $supplyId = $supply->supplyId;
            $this->assertObjectHasAttribute('orders', $this->Marketplace()->getSupplyOrders($supplyId));
        }
    }

    public function test_getStockList()
    {
        $result = $this->Marketplace()->getStockList(1, 10);
        $this->assertObjectHasAttribute('stocks', $result);
        $this->assertObjectHasAttribute('total', $result);
    }

    public function test_getWarehouses()
    {
        $result = $this->Marketplace()->getWarehouses();

        $this->assertTrue(is_array($result));
    }

    public function test_getOrders()
    {
        $date = (new DateTime('2020-01-01'));
        $result = $this->Marketplace()->getOrders(1, 100, $date);

        $this->assertObjectHasAttribute('orders', $result);
        $this->assertObjectHasAttribute('total', $result);

        $this->expectException(InvalidArgumentException::class);
        $this->Marketplace()->getOrders(1, 100, $date, 333);
    }

    public function test_getOrder()
    {
        $order1 = $this->getRandomOrder();

        $result = $this->Marketplace()->getOrder($order1->orderId);
        $this->assertObjectHasAttribute('orders', $result);
        $this->assertObjectHasAttribute('total', $result);
        $order2 = array_shift($result->orders);

        $this->assertEquals($order1->orderId, $order2->orderId);
    }

    public function test_updateOrderStatus()
    {
        $order = $this->getRandomOrder();
        $result = $this->Marketplace()->updateOrderStatus([[
            'orderId' => $order->orderId,
            'status' => $order->status,
        ]]);
        $this->assertFalse($result->error);
    }

    public function test_getOrdersStickers()
    {
        $order = $this->getRandomOrder(OrderStatus::COMPLETED);
        $result = $this->Marketplace()->getOrdersStickers([(int) $order->orderId]);

        $this->assertFalse($result->error);
        $this->assertObjectHasAttribute('sticker', $result->data[0]);
    }

    public function test_getOrdersPdfStickers()
    {
        $order = $this->getRandomOrder(OrderStatus::COMPLETED);
        $result = $this->Marketplace()->getOrdersPdfStickers([(int) $order->orderId]);

        $this->assertFalse($result->error);
        $this->assertObjectHasAttribute('file', $result->data);
    }

    private function getRandomOrder($status = -1)
    {
        $date = (new DateTime('2020-01-01'));
        $result = $this->Marketplace()->getOrders(1, 1000, $date, $status);

        $count = count($result->orders);
        if ($count) {
            return $result->orders[random_int(0, $count - 1)];
        }

        $this->markTestSkipped('No completed orders in account');
    }

}
