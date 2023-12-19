<?php

namespace Dakword\WBSeller\Tests\ApiClient;

use Dakword\WBSeller\API\Endpoint\Content;
use Dakword\WBSeller\API\Endpoint\Subpoint\Tags;
use Dakword\WBSeller\Tests\ApiClient\TestCase;
use Dakword\WBSeller\Exception\ApiTimeRestrictionsException;
use InvalidArgumentException;

class ContentTest extends TestCase
{

    private function getCardsList($limit = 10)
    {
        try {
            $result = $this->Content()->getCardsList('', $limit);
        } catch (ApiTimeRestrictionsException $exc) {
            $this->markTestSkipped($exc->getMessage());
        }

        $this->assertIsObject($result);
        $this->assertObjectHasAttribute('cards', $result);

        if (count($result->cards) == 0) {
            $this->markTestSkipped('No cards in account');
        }
        return $result->cards;
    }

    public function test_Class()
    {
        $this->assertInstanceOf(Content::class, $this->Content());
    }

    public function test_getCardsList()
    {
        $limit = 5;
        $Content = $this->Content();

        try {
            $result1 = $Content->getCardsList('', $limit);
        } catch (ApiTimeRestrictionsException $exc) {
            $this->markTestSkipped($exc->getMessage());
        }

        $this->assertObjectHasAttribute('cards', $result1);
        if ($result1->cursor->total == $limit) {
            $result2 = $Content->getCardsList('', $limit, $result1->cursor->updatedAt, $result1->cursor->nmID);
            $this->assertObjectHasAttribute('cursor', $result2);
        }
    }

    public function test_errorCardsList()
    {
        try {
            $result = $this->Content()
                ->getErrorCardsList();
        } catch (ApiTimeRestrictionsException $exc) {
            $this->markTestSkipped($exc->getMessage());
        }

        $this->assertIsArray($result->data);
    }

    public function test_getCardByVendorCode()
    {
        $cards = $this->getCardsList(1);
        $card = array_shift($cards);

        $result1 = $this->Content()->getCardByVendorCode($card->vendorCode);

        $this->assertTrue(in_array($card->vendorCode, array_column($result1->cards, 'vendorCode')));
    }

    public function test_generateBarcodes()
    {
        try {
            $result = $this->Content()
                ->generateBarcodes(2);
        } catch (ApiTimeRestrictionsException $exc) {
            $this->markTestSkipped($exc->getMessage());
        }

        $this->assertCount(2, $result->data);
        $this->assertEquals(13, strlen($result->data[0]));
    }

    public function test_getCardsLimits()
    {
        $result = $this->Content()->getCardsLimits();
        $this->assertFalse($result->error);

        if(!$result->error) {
            $this->assertObjectHasAttribute('data', $result);
            $this->assertObjectHasAttribute('freeLimits', $result->data);
            $this->assertObjectHasAttribute('paidLimits', $result->data);
        }
    }

    public function test_getTrashList()
    {
        $result = $this->Content()->Trash()->list();
        $this->assertObjectHasAttribute('cards', $result);
        $this->assertObjectHasAttribute('cursor', $result);
        $this->assertIsArray($result->cards);
    }

    public function test_addCardNomenclature_ERROR()
    {
        $result = $this->Content()->addCardNomenclature('TEST', []);
        $this->assertEquals('See https://openapi.wb.ru', $result);
    }

    public function test_createCard_ERROR()
    {
        $Content = $this->Content();

        $result1 = $Content->createCard([
            'vendorCode' => 'test',
            'variants' => [],
        ]);
        $this->assertTrue($result1->error);
        $this->assertEquals('The request format is incorrect, the number of product items created should not be 0', $result1->errorText);

        $result2 = $Content->createCards([
            [
                'subjectID' => 105,
                'variants' => [[
                    'vendorCode' => 'test2',
                    'title' => 'test2',
                    'description' => 'test2',
                    'description' => 'test2',
                    'brand' => 'test2',
                    'dimensions' => [],
                    'characteristics' => [],
                    'sizes' => [],
                ]],
            ]
        ]);
        $this->assertTrue($result2->error);
    }

    public function test_updateCard()
    {
        $listCards = $this->getCardsList();
        $listCard = array_shift($listCards);
        $cardsList = $this->Content()->getCardByVendorCode($listCard->vendorCode);
        $cards = array_filter($cardsList->cards, fn($card) => $card->vendorCode == $listCard->vendorCode);
        $card = array_shift($cards);
        if($card) {
            $result = $this->Content()->updateCard((array)$card);
            $this->assertFalse($result->error);
        } else {
            $this->markTestSkipped('No card found');
        }
    }

    public function test_searchCategory()
    {
        $result = $this->Content()
            ->searchCategory('СекС');

        $this->assertFalse($result->error);
        $this->assertTrue(in_array('Секс куклы', array_column($result->data, 'subjectName')));
    }

    public function test_getParentCategories()
    {
        $result = $this->Content()
            ->getParentCategories();

        $this->assertFalse($result->error);
        $this->assertTrue(in_array('Электроника', array_column($result->data, 'name')));
    }

    public function test_getCategoryCharacteristics()
    {
        $result = $this->Content()
            ->getCategoryCharacteristics(105);

        $this->assertFalse($result->error);
        $this->assertTrue(in_array(4, array_column($result->data, 'charcID')));
    }

    public function test_getDirectories()
    {
        $result = $this->Content()
            ->getDirectory('colors');

        $this->assertTrue(in_array('черный', array_column($result->data, 'name')));

        $this->expectException(InvalidArgumentException::class);
        $this->Content()->getDirectory('foo');
    }

    public function test_getDirectoryColors()
    {
        $this->assertTrue(in_array('зеленый', array_column($this->Content()->getDirectoryColors()->data, 'name')));
    }

    public function test_getDirectoryKinds()
    {
        $this->assertTrue(in_array('Мужской', $this->Content()->getDirectoryKinds()->data));
    }

    public function test_getDirectoryCountries()
    {
        $this->assertTrue(in_array('Индия', array_column($this->Content()->getDirectoryCountries()->data, 'name')));
    }

    public function test_getDirectorySeasons()
    {
        $this->assertTrue(in_array('лето', $this->Content()->getDirectorySeasons()->data));
    }

    public function test_getDirectoryTNVED()
    {
        $this->assertFalse($this->Content()->searchDirectoryTNVED(105)->error);
    }

    public function test_moveNms()
    {
        $result = $this->Content()->moveNms(123456, [123, 456, 789]);

        $this->assertTrue($result->error);
        $this->assertEquals('target imt not found', $result->errorText);
    }

    public function test_removeNms()
    {
        $result = $this->Content()->removeNms([123, 456, 789]);

        $this->assertTrue($result->error);
        $this->assertEquals('Invalid item card ID specified', $result->errorText);
    }

}
