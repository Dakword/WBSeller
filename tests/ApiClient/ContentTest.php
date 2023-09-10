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
            $result = $this->Content()
                ->getCardsList('', $limit);
        } catch (ApiTimeRestrictionsException $exc) {
            $this->markTestSkipped($exc->getMessage());
        }

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

        try {
            $result1 = $Content->getCardsList('', $limit);
        } catch (ApiTimeRestrictionsException $exc) {
            $this->markTestSkipped($exc->getMessage());
        }

        $this->assertObjectHasAttribute('data', $result1);
        if ($result1->data && $result1->data->cursor->total == $limit) {
            $result2 = $Content->getCardsList('', $limit, -1, 'updateAt', false, $result1->data->cursor->updatedAt, $result1->data->cursor->nmID);
            $this->assertObjectHasAttribute('cursor', $result2->data);
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
        $result = $this->Content()->getTrashList();
        $this->assertFalse($result->error);

        if(!$result->error) {
            $this->assertObjectHasAttribute('data', $result);
            $this->assertObjectHasAttribute('cards', $result->data);
            $this->assertIsArray($result->data->cards);
        }
    }

    public function test_addCardNomenclature_ERROR()
    {
        $result = $this->Content()->addCardNomenclature('TEST', []);
        $this->assertTrue($result->error);
        $this->assertEquals('Неправильный формат запроса, кол-во создаваемых карточек товаров не должно быть 0', $result->errorText);
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
        $this->assertEquals('Характеристика Предмет обязательна для заполнения', $result1->errorText);

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
            ->searchCategory('СекС');

        $this->assertFalse($result->error);
        $this->assertTrue(in_array('Секс куклы', array_column($result->data, 'objectName')));
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
            ->getCategoryCharacteristics('Секс машины');

        $this->assertFalse($result->error);
        $this->assertTrue(in_array('Упаковка', array_column($result->data, 'name')));
    }

    public function test_searchCategoryCharacteristics()
    {
        $result = $this->Content()
            ->getCategoriesCharacteristics('Товары для взрослых');

        $this->assertFalse($result->error);
        $this->assertTrue(in_array('Секс куклы', array_column($result->data, 'objectName')));
        $this->assertTrue(in_array('Упаковка', array_column($result->data, 'name')));
    }

    public function test_getDirectories()
    {
        $result1 = $this->Content()
            ->getDirectory('/brands', ['pattern' => 'app', 'top' => 500]);

        $this->assertTrue(in_array('Apple', $result1->data));

        $result2 = $this->Content()
            ->getDirectory('colors');

        $this->assertTrue(in_array('черный', array_column($result2->data, 'name')));

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

    public function test_getDirectoryBrands()
    {
        $this->assertTrue(in_array('LTD Corp.Ink', $this->Content()->searchDirectoryBrands('ltd', 100)->data));
    }

    public function test_getDirectoryTNVED()
    {
        $this->assertTrue(in_array('6405100009', array_column($this->Content()->searchDirectoryTNVED('Кроссовки')->data, 'tnvedName')));
        $this->assertCount(2, $this->Content()->searchDirectoryTNVED('Кроссовки', '64059')->data);
        $this->assertCount(1, $this->Content()->searchDirectoryTNVED('Кроссовки', '6405909000')->data);
    }

    public function test_updateMedia()
    {
        $cards = $this->getCardsList();
        $card = array_shift($cards);

        $mediaFiles = $card->mediaFiles;
        $result = $this->Content()->updateMedia($card->vendorCode, $mediaFiles);

        $this->assertFalse($result->error);
    }

    public function test_moveNms()
    {
        $result = $this->Content()->moveNms(123456, [123, 456, 789]);

        $this->assertTrue($result->error);
        $this->assertEquals('Внутренняя ошибка', $result->errorText);
    }

    public function test_removeNms()
    {
        $result = $this->Content()->removeNms([123, 456, 789]);

        $this->assertTrue($result->error);
        $this->assertEquals('Указан несуществующий nmID карточки товара', $result->errorText);
    }

}
