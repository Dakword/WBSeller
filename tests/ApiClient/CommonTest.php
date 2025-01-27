<?php

namespace Dakword\WBSeller\Tests\ApiClient;

use Dakword\WBSeller\API\Endpoint\Common;
use Dakword\WBSeller\Tests\ApiClient\TestCase;

class CommonTest extends TestCase
{
    public function test_Class()
    {
        $this->assertInstanceOf(Common::class, $this->Common());
    }

    public function test_sellerInfo()
    {
        $result = $this->Common()->sellerInfo();

        $this->assertIsObject($result);
        $this->assertObjectHasAttribute('name', $result);
        $this->assertObjectHasAttribute('tradeMark', $result);
    }

}
