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
            'keys' => [
                'apikey' => 'XXX',
                'statkey' => 'YYY',
                'advkey' => 'ZZZ',
            ]
        ]);
        $API->Prices()->getPrices();
    }

    public function test_API_Locale()
    {
        $API1 = new API([
            'keys' => [
                'apikey' => 'XXX',
                'statkey' => 'YYY',
                'advkey' => 'ZZZ',
            ],
            'locale' => 'en'
        ]);
        $this->assertEquals('en', $API1->getLocale());

        $API2 = new API();
        $this->assertEquals('ru', $API2->getLocale());

        $API3 = new API();
        $API3->setLocale('en');
        $this->assertEquals('en', $API3->getLocale());
    }

    public function test_Retry()
    {
        $Statistics = $this->Statistics();
        $this->assertInstanceOf(Statistics::class, $Statistics->retryOnTooManyRequests(3, 500));
    }

    public function test_Ping()
    {
        $Content = $this->Content();
        $result = $Content->ping();

        $this->assertIsObject($result);
        $this->assertObjectHasAttribute('Status', $result);
        $this->assertEquals('OK', $result->Status);
    }

}
