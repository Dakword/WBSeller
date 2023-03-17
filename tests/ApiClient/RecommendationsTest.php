<?php

namespace Dakword\WBSeller\Tests\ApiClient;

use Dakword\WBSeller\API\Endpoint\Recommendations;
use Dakword\WBSeller\Tests\ApiClient\TestCase;

class RecommendationsTest extends TestCase
{

    public function test_Class()
    {
        $this->assertInstanceOf(Recommendations::class, $this->API()->Recommendations());
    }

    public function test_list()
    {
        $nmIds = [1234567, 7654321];
        $result = $this->Recommendations()->list($nmIds);
        $this->assertIsArray($result);
        $this->assertTrue(in_array($nmIds[0], array_keys($result)));
        $this->assertTrue(in_array($nmIds[1], array_keys($result)));
    }

    public function test_add()
    {
        $recom = $this->Recommendations();
        $recom->add([123456 => [12345, 67890]]);

        $this->assertEquals(200, $recom->responseCode());
    }

    public function test_delete()
    {
        $recom = $this->Recommendations();
        $recom->delete([123456 => [12345, 67890]]);

        $this->assertEquals(200, $recom->responseCode());
    }

}
