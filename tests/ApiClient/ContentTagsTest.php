<?php

namespace Dakword\WBSeller\Tests\ApiClient;

use Dakword\WBSeller\API\Endpoint\Subpoint\Tags;
use Dakword\WBSeller\Tests\ApiClient\TestCase;

class ContentTagsTest extends TestCase
{
    private $Tags;
    
    public function setUp(): void
    {
        parent::setUp();
        $this->Tags = $this->ContentTags();
    }
    public function test_Class()
    {
        $this->assertInstanceOf(Tags::class, $this->Content()->Tags());
    }

    public function test_list()
    {
        $result = $this->Tags->list();

        $this->assertFalse($result->error);
        $this->assertIsArray($result->data);
    }

    public function test_create_update_delete()
    {
        $Tags = $this->Tags;
        $result1 = $Tags->create('ХИТ', 'FEE0E0');

        $this->assertFalse($result1->error);

        if(!$result1->error) {
            $this->assertObjectHasAttribute('data', $result1);
            $id = $result1->data;
            $this->assertIsInt($id);

            $result2 = $Tags->create('ХИТ', 'FEE0E0');
            $this->assertTrue($result2->error);
            $this->assertEquals('tag already exists', $result2->errorText);

            $result3 = $Tags->update($id, 'МЕГАХИТ', 'FFECC7');
            $this->assertFalse($result3->error);
            
            $result4 = $Tags->delete($id);
            $this->assertFalse($result4->error);
        }
    }

    public function test_delete()
    {
        $result = $this->Tags->delete(12345);
        $this->assertTrue($result->error);
    }

}
