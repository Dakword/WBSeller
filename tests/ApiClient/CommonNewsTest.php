<?php

namespace Dakword\WBSeller\Tests\ApiClient;

use Dakword\WBSeller\API\Endpoint\Subpoint\News;
use Dakword\WBSeller\Tests\ApiClient\TestCase;

class CommonNewsTest extends TestCase
{
    public function test_Class()
    {
        $this->assertInstanceOf(News::class, $this->Common()->News());
    }

    public function test_news()
    {
        $result1 = $this->CommonNews()->fromDate(new \DateTime('2024-08-01'));
        $this->assertIsArray($result1);

        if($result1) {
            $firstNews1 = array_shift($result1);
            $result2 = $this->CommonNews()->fromId($firstNews1->id);

            $this->assertIsArray($result2);
            $firstNews2 = array_shift($result2);
        } else {
            $result2 = $this->CommonNews()->fromId(5000);
            $this->assertIsArray($result2);
        }
    }

}
