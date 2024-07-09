<?php

namespace Dakword\WBSeller\Tests\ApiClient;

use Dakword\WBSeller\API\Endpoint\Subpoint\PaidStorage;
use Dakword\WBSeller\Tests\ApiClient\TestCase;

class AnaliticsPaidStorageTest extends TestCase
{
    private $Analitics;
    
    public function setUp(): void
    {
        parent::setUp();
        $this->Analitics = $this->Analytics();
    }
    public function test_Class()
    {
        $this->assertInstanceOf(PaidStorage::class, $this->Analitics->PaidStorage());
    }

    public function test_makeReport()
    {
        $result = $this->Analitics->PaidStorage()->makeReport(new \DateTime('2024-06-01'), new \DateTime('2024-06-07'));
        $this->assertIsString($result);
    }

}
