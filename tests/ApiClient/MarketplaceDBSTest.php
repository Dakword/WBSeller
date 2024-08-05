<?php

namespace Dakword\WBSeller\Tests\ApiClient;

use Dakword\WBSeller\API\Endpoint\Subpoint\DBS;
use Dakword\WBSeller\Tests\ApiClient\TestCase;

class MarketplaceDBSTest extends TestCase
{
    private $DBS;
    
    public function setUp(): void
    {
        parent::setUp();
        $this->DBS = $this->MarketplaceDBS();
    }
    public function test_Class()
    {
        $this->assertInstanceOf(DBS::class, $this->Marketplace()->DBS());
    }

    public function test_list()
    {
        $result = $this->DBS->confirm();
        $this->assertIsFalse($result);
    }

}
