<?php

namespace Dakword\WBSeller\Tests\Query;

use Dakword\WBSeller\Query\CardsList,
    Dakword\WBSeller\Exception\ApiClientException,
    Dakword\WBSeller\Exception\ApiTimeRestrictionsException;
use Dakword\WBSeller\Tests\Query\TestCase;
use InvalidArgumentException;

class CardsListTest extends TestCase
{

    public function test_Class()
    {
        $this->assertInstanceOf(CardsList::class, $this->Query()->CardsList());
    }

    public function test_CardsList()
    {
        try {
            $result = $this->API()->Content()->getCardsList('', 5);
        } catch (ApiTimeRestrictionsException $exc) {
            $this->markTestSkipped($exc->getMessage());
        }

        $cards = $result->data->cards;

        if (!$cards) {
            $all = $this->Query()->CardsList()->getAll();
            $this->assertCount(0, $all);
        } else {
            $card = array_pop($cards);

            $all_cards_1 = $this->Query()->CardsList()->perPage(100)->sortDesc()->find($card->nmID)->getAll();
            $this->assertTrue(in_array($card->nmID, array_column($all_cards_1, 'nmID')));

            if ($card->mediaFiles) {
                $all_cards_2 = $this->Query()->CardsList()->perPage(10)->sortDesc()->withPhoto()->find($card->nmID)->getAll();
                $this->assertTrue(in_array($card->nmID, array_column($all_cards_2, 'nmID')));
            } else {
                $all_cards_2 = $this->Query()->CardsList()->perPage(10)->sortDesc()->withOutPhoto()->find($card->nmID)->getAll();
                $this->assertTrue(in_array($card->nmID, array_column($all_cards_2, 'nmID')));
            }
            $all_cards_3 = $this->Query()->CardsList()->find(mb_substr($card->vendorCode, 0, -3))->getAll();
            $this->assertTrue(in_array($card->vendorCode, array_column($all_cards_3, 'vendorCode')));
        }

        $this->expectException(InvalidArgumentException::class);
        $this->Query()->CardsList()->perPage(2000)->getAll();
    }

    public function test_CardsListExceptions()
    {
        $this->expectException(ApiClientException::class);
        $this->Query()->CardsList()->perPage(555)->getCursor();
        
        $this->expectException(ApiClientException::class);
        $this->Query()->CardsList()->find('sex')->getNext();
    }
}
