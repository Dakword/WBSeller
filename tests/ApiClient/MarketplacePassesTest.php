<?php

namespace Dakword\WBSeller\Tests\ApiClient;

use Dakword\WBSeller\API\Endpoint\Subpoint\Passes;
use Dakword\WBSeller\Tests\ApiClient\TestCase;

class MarketplacePassesTest extends TestCase
{
    private $Passes;
    
    public function setUp(): void
    {
        parent::setUp();
        $this->Passes = $this->MarketplacePasses();
    }
    public function test_Class()
    {
        $this->assertInstanceOf(Passes::class, $this->Marketplace()->Passes());
    }
    public function test_list()
    {
        $result = $this->Passes->list();
        $this->assertIsArray($result);
        if($result) {
            $pass = array_shift($result);
            $this->assertObjectHasAttribute('id', $pass);
            $this->assertObjectHasAttribute('officeId', $pass);
            $this->assertObjectHasAttribute('dateEnd', $pass);
        }
    }

    public function test_offices()
    {
        $result = $this->Passes->offices();
        $this->assertIsArray($result);
        
        $office = array_shift($result);
        $this->assertObjectHasAttribute('id', $office);
        $this->assertObjectHasAttribute('name', $office);
        $this->assertObjectHasAttribute('address', $office);
    }

    public function test_crud()
    {
        $result = $this->Passes->offices();
        $office = array_shift($result);

        $pass = $this->Passes->create($office->id, 'Газелька', 'X999XX99', 'Имя', 'Фамилия');
        $this->assertObjectHasAttribute('id', $pass);
        
        $updated = $this->Passes->update($pass->id, $office->id, 'Газель', 'О777ММ77', 'Водитель', 'Газели');
        $this->assertTrue($updated);
        
        $deleted = $this->Passes->delete($pass->id);
        $this->assertTrue($deleted);
    }
}
