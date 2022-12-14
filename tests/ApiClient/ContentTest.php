<?php

namespace Dakword\WBSeller\Tests\ApiClient;

use Dakword\WBSeller\API\Endpoint\Content;
use Dakword\WBSeller\Tests\ApiClient\TestCase;
use InvalidArgumentException;

class ContentTest extends TestCase
{

    private function getCardsList($limit = 10)
    {
        $result = $this->Content()
            ->getCardsList('', $limit);

        $this->assertIsObject($result);
        $this->assertObjectHasAttribute('data', $result);

        if (count($result->data->cards) == 0) {
            $this->markTestSkipped('No cards in account');
        }
        return $result->data->cards;
    }

    public function test_Class()
    {
        $this->assertInstanceOf(Content::class, $this->Content());
    }

    public function test_getCardsList()
    {
        $limit = 5;
        $Content = $this->Content();
        $result1 = $Content->getCardsList('', $limit);
        $this->assertObjectHasAttribute('cursor', $result1->data);
        if ($result1->data->cursor->total == $limit) {
            $result2 = $Content->getCardsList('', $limit, -1, 'updateAt', false, $result1->data->cursor->updatedAt, $result1->data->cursor->nmID);
            $this->assertObjectHasAttribute('cursor', $result2->data);
        }
    }

    public function test_errorCardsList()
    {
        $result = $this->Content()
            ->getErrorCardsList();

        $this->assertIsArray($result->data);
    }

    public function test_getCardsByVendorCodes()
    {
        $cards = $this->getCardsList();
        $card = array_shift($cards);

        $result1 = $this->Content()
            ->getCardsByVendorCodes($card->vendorCode);

        $this->assertTrue(in_array($card->vendorCode, array_column($result1->data, 'vendorCode')));
    }

    public function test_generateBarcodes()
    {
        $result = $this->Content()
            ->generateBarcodes(2);

        $this->assertCount(2, $result->data);
        $this->assertEquals(13, strlen($result->data[0]));
    }

    public function test_addCardNomenclature_ERROR()
    {
        $result = $this->Content()->addCardNomenclature('TEST', []);
        $this->assertTrue($result->error);
        $this->assertEquals('???????????????????? ????????????', $result->errorText);
    }

    public function test_createCard_ERROR()
    {
        $Content = $this->Content();

        $result1 = $Content->createCard([
            'vendorCode' => 'test',
            'characteristics' => [],
            'sizes' => []
        ]);
        $this->assertTrue($result1->error);
        $this->assertEquals('???????????????????????????? ?????????????? ?????????????????????? ?????? ????????????????????', $result1->errorText);

        $result2 = $Content->createCards([
            [
                'vendorCode' => 'test1',
                'characteristics' => [],
                'sizes' => []
            ], [
                'vendorCode' => 'test2',
                'characteristics' => [],
                'sizes' => []
            ]
        ]);
        $this->assertTrue($result2->error);
    }

    public function test_updateCard()
    {
        $listCards = $this->getCardsList();
        $listCard = array_shift($listCards);
        $cardsList = $this->Content()->getCardsByVendorCodes($listCard->vendorCode);
        $cards = array_filter($cardsList->data, fn($card) => $card->vendorCode == $listCard->vendorCode);
        $card = array_shift($cards);
        
        if($card) {
            $result = $this->Content()->updateCards([$card]);
            $this->assertFalse($result->error);
        } else {
            $this->markTestSkipped('No card found');
        }
    }

    public function test_searchCategory()
    {
        $result = $this->Content()
            ->searchCategory('????????');

        $this->assertFalse($result->error);
        $this->assertTrue(in_array('???????? ??????????', array_column($result->data, 'objectName')));
    }

    public function test_getParentCategories()
    {
        $result = $this->Content()
            ->getParentCategories();

        $this->assertFalse($result->error);
        $this->assertTrue(in_array('??????????????????????', array_column($result->data, 'name')));
    }

    public function test_getCategoryCharacteristics()
    {
        $result = $this->Content()
            ->getCategoryCharacteristics('???????? ????????????');

        $this->assertFalse($result->error);
        $this->assertTrue(in_array('????????????????', array_column($result->data, 'name')));
    }

    public function test_searchCategoryCharacteristics()
    {
        $result = $this->Content()
            ->getCategoriesCharacteristics('???????????? ?????? ????????????????');

        $this->assertFalse($result->error);
        $this->assertTrue(in_array('???????? ??????????', array_column($result->data, 'objectName')));
        $this->assertTrue(in_array('????????????????', array_column($result->data, 'name')));
    }

    public function test_getDirectories()
    {
        $result1 = $this->Content()
            ->getDirectory('/brands', ['pattern' => 'app', 'top' => 500]);

        $this->assertTrue(in_array('Apple', $result1->data));

        $result2 = $this->Content()
            ->getDirectory('colors');

        $this->assertTrue(in_array('????????????', array_column($result2->data, 'name')));

        $this->expectException(InvalidArgumentException::class);
        $this->Content()->getDirectory('foo');
    }

    public function test_getDirectoryColors()
    {
        $this->assertTrue(in_array('??????????????', array_column($this->Content()->getDirectoryColors()->data, 'name')));
    }

    public function test_getDirectoryKinds()
    {
        $this->assertTrue(in_array('??????????????', $this->Content()->getDirectoryKinds()->data));
    }

    public function test_getDirectoryCountries()
    {
        $this->assertTrue(in_array('??????????', array_column($this->Content()->getDirectoryCountries()->data, 'name')));
    }

    public function test_searchDirectoryCollections()
    {
        $this->assertTrue(in_array('????????-????????-??????????-??????????', array_column($this->Content()->searchDirectoryCollections('????????-????????', 100)->data, 'name')));
    }

    public function test_getDirectorySeasons()
    {
        $this->assertTrue(in_array('????????', $this->Content()->getDirectorySeasons()->data));
    }

    public function test_searchDirectoryContents()
    {
        $this->assertTrue(in_array('?????????????? ?????? ??????????????', array_column($this->Content()->searchDirectoryContents('??????????????', 50)->data, 'name')));
    }

    public function test_searchDirectoryConsists()
    {
        $this->assertTrue(in_array('?????? ????????????????', array_column($this->Content()->searchDirectoryConsists('??????????????', 50)->data, 'name')));
    }

    public function test_getDirectoryBrands()
    {
        $this->assertTrue(in_array('LTD Corp.Ink', $this->Content()->searchDirectoryBrands('ltd', 100)->data));
    }

    public function test_getDirectoryTNVED()
    {
        $this->assertTrue(in_array('6405100009', array_column($this->Content()->searchDirectoryTNVED('??????????????????')->data, 'tnvedName')));
        $this->assertCount(2, $this->Content()->searchDirectoryTNVED('??????????????????', '64059')->data);
        $this->assertCount(1, $this->Content()->searchDirectoryTNVED('??????????????????', '6405909000')->data);
    }

    public function test_updateMedia()
    {
        $cards = $this->getCardsList();
        $card = array_shift($cards);

        $mediaFiles = $card->mediaFiles;
        $result = $this->Content()->updateMedia($card->vendorCode, $mediaFiles);

        $this->assertFalse($result->error);
    }

}
