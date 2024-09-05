<?php

namespace Dakword\WBSeller\Tests\ApiClient;

use Dakword\WBSeller\API\Endpoint\Tariffs;
use Dakword\WBSeller\Tests\ApiClient\TestCase;

/**
 * @coversDefaultClass \Dakword\WBSeller\Endpoints\Tariffs
 */
class TariffsTest extends TestCase
{

    public function test_Class()
    {
        $this->assertInstanceOf(Tariffs::class, $this->API()->Tariffs());
    }

    /**
     * @covers ::Box()
     */
    public function test_box()
    {
        $result = $this->API()->Tariffs()->box(new \DateTime());

        $this->assertObjectHasAttribute('dtNextBox', $result);
        $this->assertObjectHasAttribute('dtTillMax', $result);
        $this->assertObjectHasAttribute('warehouseList', $result);
    }

    /**
     * @covers ::Pallet()
     */
    public function test_pallet()
    {
        $result = $this->API()->Tariffs()->pallet(new \DateTime());

        $this->assertObjectHasAttribute('dtNextPallet', $result);
        $this->assertObjectHasAttribute('dtTillMax', $result);
        $this->assertObjectHasAttribute('warehouseList', $result);
    }

    /**
     * @covers ::Return()
     */
    public function test_return()
    {
        $result = $this->API()->Tariffs()->return(new \DateTime());

        $this->assertObjectHasAttribute('dtNextDeliveryDumpKgt', $result);
        $this->assertObjectHasAttribute('dtNextDeliveryDumpSrg', $result);
        $this->assertObjectHasAttribute('dtNextDeliveryDumpSup', $result);
        $this->assertObjectHasAttribute('warehouseList', $result);
    }

}
