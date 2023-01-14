<?php

namespace Dakword\WBSeller\Tests\ApiClient;

use Dakword\WBSeller\API\Endpoint\Adv;
use Dakword\WBSeller\Enum\AdvertStatus;
use Dakword\WBSeller\Enum\AdvertType;
use Dakword\WBSeller\Tests\ApiClient\TestCase;

class AdvTest extends TestCase
{

    public function test_Class()
    {
        $this->assertInstanceOf(Adv::class, $this->API()->Adv());
    }

    public function test_count()
    {
        $Adv = $this->Adv();
        $result = $Adv->count();

        $this->assertIsObject($result);
        $this->assertObjectHasAttribute('all', $result);
        $this->assertIsArray($result->adverts);
    }

    public function test_advertsList()
    {
        $Adv = $this->Adv();
        $result = $Adv->advertsList(AdvertStatus::PLAY, AdvertType::ON_CATALOG, 10, 0);

        $this->assertIsArray($result);
    }

    public function test_advert()
    {
        $Adv = $this->Adv();
        $result = $Adv->advert(555);

        $this->assertIsObject($result);
        $this->assertObjectHasAttribute('advertId', $result);
        $this->assertIsArray($result->params);
    }

    public function test_cpm()
    {
        $Adv = $this->Adv();
        $result = $Adv->cpm(AdvertType::ON_SEARCH, 123456);

        $this->assertIsArray($result);
    }

    public function test_updateCpm()
    {
        $Adv = $this->Adv();
        $result = $Adv->updateCpm(123456, AdvertType::ON_HOME_RECOM, 123456, 123456);

        $this->assertFalse($result);
    }

    public function test_start()
    {
        $Adv = $this->Adv();
        $result = $Adv->start(123456);

        $this->assertFalse($result);
    }

    public function test_pause()
    {
        $Adv = $this->Adv();
        $result = $Adv->pause(123456);

        $this->assertFalse($result);
    }

}
