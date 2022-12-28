<?php

namespace Dakword\WBSeller\Tests\ApiClient;

use Dakword\WBSeller\API;
use Dakword\WBSeller\API\Endpoint\Statistics;
use Dakword\WBSeller\Exception\ApiClientException;
use Dakword\WBSeller\Tests\ApiClient\TestCase;

class ApiTest extends TestCase
{

    public function test_API()
    {
        $API = $this->API();
        $this->assertInstanceOf(API::class, $API);
    }

    public function test_API_BadKey()
    {
        $this->expectException(ApiClientException::class);
        $this->expectExceptionCode(401);

        $API = new API([
            'apikey' => 'XXX',
            'statkey' => 'YYY',
        ]);
        $API->Prices()->getPrices();
    }

    public function test_Retry()
    {
        $Statistics = $this->Statistics();
        $this->assertInstanceOf(Statistics::class, $Statistics->retryOnTooManyRequests(3, 500));
    }

}
