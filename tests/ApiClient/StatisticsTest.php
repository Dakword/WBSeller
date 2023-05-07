<?php

namespace Dakword\WBSeller\Tests\ApiClient;

use Dakword\WBSeller\API\Endpoint\Statistics;
use Dakword\WBSeller\Tests\ApiClient\TestCase;
use DateTime;
use InvalidArgumentException;

class StatisticsTest extends TestCase
{

    public function test_Class()
    {
        $this->assertInstanceOf(Statistics::class, $this->API()->Statistics());
    }

    public function test_Incomes()
    {
        $result = $this->Statistics()->incomes(new DateTime());
        $this->assertIsArray($result);
    }

    public function test_Stocks()
    {
        $result = $this->Statistics()->stocks(new DateTime());
        $this->assertIsArray($result);
    }

    public function test_Orders()
    {
        $result1 = $this->Statistics()->retryOnTooManyRequests()->ordersFromDate(new DateTime('2022-10-01'));
        $this->assertIsArray($result1);

        $result2 = $this->Statistics()->retryOnTooManyRequests()->ordersOnDate(new DateTime());
        $this->assertIsArray($result2);
    }

    public function test_Sales()
    {
        $result1 = $this->Statistics()->retryOnTooManyRequests()->salesFromDate(new DateTime('2022-10-01'));
        $this->assertIsArray($result1);
        $result2 = $this->Statistics()->retryOnTooManyRequests()->salesOnDate(new DateTime('2022-10-20'));
        $this->assertIsArray($result2);
    }

    public function test_DetailReport()
    {
        try {
            $result1 = $this->Statistics()->detailReport(new DateTime('2022-10-01'), new DateTime(), 100);
            $this->assertIsArray($result1);
        } catch (\Exception $exc) {
            if($exc instanceof \Dakword\WBSeller\Exception\ApiTimeRestrictionsException) {
                $this->assertTrue(true);
            } else {
                throw $exc;
            }
        }
        $this->expectException(InvalidArgumentException::class);
        $this->Statistics()->detailReport(new DateTime('2022-01-01'), new DateTime(), 100_001);
    }

}
