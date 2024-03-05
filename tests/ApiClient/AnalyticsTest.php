<?php

namespace Dakword\WBSeller\Tests\ApiClient;

use Dakword\WBSeller\API\Endpoint\Analytics;
use Dakword\WBSeller\Tests\ApiClient\TestCase;

class AnalyticsTest extends TestCase
{

    private $Analytics;
    
    public function setUp(): void
    {
        parent::setUp();
        
        $this->Analytics = $this->Analytics();
    }

    public function test_Class()
    {
        $this->assertInstanceOf(Analytics::class, $this->API()->Analytics());
    }

    public function test_nmReportDetail()
    {
        $result1 = $this->Analytics->nmReportDetail(new \DateTime('2024-01-01 00:00:00'), new \DateTime());

        $this->assertFalse($result1->error);
        $this->assertIsArray($result1->data->cards);
        
        $result2 = $this->Analytics->nmReportDetail(new \DateTime('2024-01-01 00:00:00'), new \DateTime(),
            [
                'nmIDs' => [1234567],
            ]
        );
        $this->assertFalse($result2->error);
        $this->assertEquals(null, $result2->data->cards);
        
    }

    //public function test_exciseReport()
    //{
    //    $result1 = $this->Analytics->exciseReport(new \DateTime('2024-01-01'), new \DateTime());
    //    
    //}

}
