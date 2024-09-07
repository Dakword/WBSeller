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
        $result1 = $this->Analytics->nmReportDetail(new \DateTime('2024-09-01 00:00:00'), new \DateTime());

        $this->assertFalse($result1->error);
        $this->assertIsArray($result1->data->cards);

        $result2 = $this->Analytics->nmReportDetail(new \DateTime('2024-09-01 00:00:00'), new \DateTime(),
            [
                'nmIDs' => [1234567],
            ]
        );
        $this->assertFalse($result2->error);
        $this->assertEquals(null, $result2->data->cards);

    }

    public function test_nmReportGrouped()
    {
        $result1 = $this->Analytics->nmReportGrouped(new \DateTime('2024-09-01'), new \DateTime());
        $this->assertFalse($result1->error);
        $this->assertIsArray($result1->data->groups);

        $result2 = $this->Analytics->nmReportGrouped(new \DateTime('2024-09-01'), new \DateTime(),
            [
                'brandNames' => ['Adidas'],
            ]
        );
        $this->assertFalse($result2->error);
        $this->assertIsArray($result2->data->groups);

    }

    public function test_nmReportDetailHistory()
    {
        $result = $this->Analytics->nmReportDetailHistory([1234567], new \DateTime('2024-09-01'), new \DateTime());

        $this->assertFalse($result->error);
        $this->assertIsArray($result->data);
    }

    public function test_nmReportGroupedHistory()
    {
        $result = $this->Analytics->nmReportGroupedHistory(new \DateTime('2024-09-01'), new \DateTime());

        $this->assertFalse($result->error);
        $this->assertIsArray($result->data);
    }

    public function test_exciseReport()
    {
        $result = $this->Analytics->exciseReport(new \DateTime('2024-09-01'), new \DateTime());

        $this->assertIsArray($result->response->data);
    }

    public function test_goodsReturn()
    {
        $result = $this->Analytics->goodsReturn(new \DateTime('2024-08-01'), new \DateTime('2024-08-31'));

        $this->assertIsArray($result);

        if($result) {
            $first = array_shift($result);
            $this->assertObjectHasAttribute('nmId', $first);
        }
    }
}
