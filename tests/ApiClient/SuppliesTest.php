<?php

namespace Dakword\WBSeller\Tests\ApiClient;

use Dakword\WBSeller\API\Endpoint\Supplies;
use Dakword\WBSeller\Tests\ApiClient\TestCase;

/**
 * @coversDefaultClass \Dakword\WBSeller\API\Endpoint\Supplies
 */
class SuppliesTest extends TestCase
{

    public function test_Class()
    {
        $this->assertInstanceOf(Supplies::class, $this->API()->Supplies());
    }

    /**
     * @covers ::ping()
     */
    public function test_ping()
    {
        $result = $this->API()->Supplies()->ping();
        var_dump($result);
        $this->assertEquals('OK', $result->Status);
    }

    /**
     * @covers ::coefficients()
     */
    public function test_coefficients()
    {
        $result = $this->API()->Supplies()->coefficients();

        $this->assertIsArray($result);
    }

    /**
     * @covers ::options()
     */
    public function test_options()
    {
        $result = $this->API()->Supplies()->options([
            ['quantity' => 1, 'barcode' => '123456']
        ]);

        $this->assertObjectHasAttribute('requestId', $result);
        $this->assertEquals('123456', $result->result[0]->barcode);
    }

    /**
     * @covers ::warehouses()
     */
    public function test_warehouses()
    {
        $result = $this->API()->Supplies()->warehouses();

        $this->assertIsArray($result);
        $this->assertTrue(count($result) > 0);
    }
}