<?php

namespace Dakword\WBSeller\Tests\ApiClient;

use Dakword\WBSeller\API\Endpoint\Subpoint\WarehouseRemains;
use Dakword\WBSeller\Tests\ApiClient\TestCase;

class AnaliticsWarehouseRemainsTest extends TestCase
{
    private $Analitics;

    public function setUp(): void
    {
        parent::setUp();
        $this->Analitics = $this->Analytics();
    }
    public function test_Class()
    {
        $this->assertInstanceOf(WarehouseRemains::class, $this->Analitics->WarehouseRemains());
    }

    public function test_makeReport()
    {
        $result = $this->Analitics->WarehouseRemains()->makeReport([]);

        $this->assertIsString($result);
    }

}
