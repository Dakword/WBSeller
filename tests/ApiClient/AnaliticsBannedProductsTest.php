<?php

namespace Dakword\WBSeller\Tests\ApiClient;

use Dakword\WBSeller\API\Endpoint\Subpoint\BannedProducts;
use Dakword\WBSeller\Tests\ApiClient\TestCase;

class AnaliticsBannedProductsTest extends TestCase
{
    private $Analitics;

    public function setUp(): void
    {
        parent::setUp();
        $this->Analitics = $this->Analytics();
    }
    public function test_Class()
    {
        $this->assertInstanceOf(BannedProducts::class, $this->Analitics->BannedProducts());
    }

    public function test_blocked()
    {
        $result = $this->Analitics->BannedProducts()->blocked();

        $this->assertIsArray($result);
    }

    public function test_shadowed()
    {
        $result = $this->Analitics->BannedProducts()->shadowed();

        $this->assertIsArray($result);
    }
}
