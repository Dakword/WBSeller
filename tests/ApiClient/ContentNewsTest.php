<?php

namespace Dakword\WBSeller\Tests\ApiClient;

use Dakword\WBSeller\API\Endpoint\Subpoint\News;
use Dakword\WBSeller\Tests\ApiClient\TestCase;

class ContentNewsTest extends TestCase
{
    public function test_Class()
    {
        $this->assertInstanceOf(News::class, $this->Content()->News());
    }

    public function test_news()
    {
        $result1 = $this->ContentNews()->fromDate(new \DateTime('2024-08-01'));
        var_dump($result1);
        $this->assertIsArray($result1);

        if($result1) {
            $firstNews1 = array_shift($result1);
            $result2 = $this->ContentNews()->fromId($firstNews1->id);

            $this->assertIsArray($result2);
            $firstNews2 = array_shift($result2);
            $this->assertEquals($firstNews1->id, $firstNews2->id);
        } else {
            $result2 = $this->ContentNews()->fromId(5000);
            $this->assertIsArray($result2);
        }
    }

}
