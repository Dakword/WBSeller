<?php

namespace Dakword\WBSeller\Tests\ApiClient;

use Dakword\WBSeller\API\Endpoint\Recommends;
use Dakword\WBSeller\Tests\ApiClient\TestCase;

class RecommendationsTest extends TestCase
{

    public function test_Class()
    {
        $this->assertInstanceOf(Recommends::class, $this->API()->Recommends());
    }

    public function test_list()
    {
        $nmIds = $this->getRealNms(2);
        $result = $this->Recommends()->list($nmIds);
        $this->assertIsArray($result);
        $this->assertTrue(in_array($nmIds[0], array_keys($result)));
        $this->assertTrue(in_array($nmIds[1], array_keys($result)));
    }

    public function test_add()
    {
        $recom = $this->Recommends();
        $recom->add([123456 => [12345, 67890]]);

        $this->assertEquals(200, $recom->responseCode());
    }

    public function test_delete()
    {
        $recom = $this->Recommends();
        $recom->delete([123456 => [12345, 67890]]);

        $this->assertEquals(200, $recom->responseCode());
    }

    public function test_update()
    {
        $recom = $this->Recommends();
        $recom->update([123456 => [12345, 67890]]);

        $this->assertEquals(200, $recom->responseCode());
    }

}
