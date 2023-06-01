<?php

namespace Dakword\WBSeller\Tests\ApiClient;

use Dakword\WBSeller\API\Endpoint\Subpoint\Warehouses;
use Dakword\WBSeller\Tests\ApiClient\TestCase;

class MarketplaceWarehousesTest extends TestCase
{
    private $Warehouses;
    
    public function setUp(): void
    {
        parent::setUp();
        $this->Warehouses = $this->MarketplaceWarehouses();
    }
    public function test_Class()
    {
        $this->assertInstanceOf(Warehouses::class, $this->Marketplace()->Warehouses());
    }

    public function test_list()
    {
        $result = $this->Warehouses->list();
        $this->assertIsArray($result);
        
        $warehouse = array_shift($result);
        $this->assertObjectHasAttribute('id', $warehouse);
        $this->assertObjectHasAttribute('name', $warehouse);
        $this->assertObjectHasAttribute('officeId', $warehouse);
    }

    public function test_offices()
    {
        $result = $this->Warehouses->offices();
        $this->assertIsArray($result);
        
        $office = array_shift($result);
        $this->assertObjectHasAttribute('id', $office);
        $this->assertObjectHasAttribute('name', $office);
        $this->assertObjectHasAttribute('address', $office);
        $this->assertObjectHasAttribute('city', $office);
        $this->assertObjectHasAttribute('longitude', $office);
        $this->assertObjectHasAttribute('latitude', $office);
        $this->assertObjectHasAttribute('selected', $office);
    }

    public function test_crud()
    {
        $result = $this->Warehouses->offices();
        $office = array_shift($result);

        $warehouse = $this->Warehouses->create('XYZ', $office->id);
        $this->assertObjectHasAttribute('id', $warehouse);
        
        $updated = $this->Warehouses->update($warehouse->id, 'ABC-Test', $office->id);
        $this->assertTrue($updated);
        
        $deleted = $this->Warehouses->delete($warehouse->id);
        $this->assertTrue($deleted);
    }
}
