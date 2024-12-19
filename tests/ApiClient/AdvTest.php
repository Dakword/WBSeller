<?php

namespace Dakword\WBSeller\Tests\ApiClient;

use Dakword\WBSeller\API\Endpoint\Adv,
    Dakword\WBSeller\Enum\AdvertStatus,
    Dakword\WBSeller\Enum\AdvertType;
use Dakword\WBSeller\Tests\ApiClient\TestCase;
use InvalidArgumentException;

class AdvTest extends TestCase
{

    private $Adv;

    public function setUp(): void
    {
        parent::setUp();

        $this->Adv = $this->Adv();
    }

    public function test_Class()
    {
        $this->assertInstanceOf(Adv::class, $this->API()->Adv());
    }

    public function test_config()
    {
        $result = $this->Adv->config();

        $this->assertIsObject($result);
        $this->assertObjectHasAttribute('config', $result);
        $this->assertIsArray($result->config);
    }

    public function test_count()
    {
        $result = $this->Adv->count();

        $this->assertIsObject($result);
        $this->assertObjectHasAttribute('all', $result);
        $this->assertIsArray($result->adverts);
    }

    public function test_advertsList()
    {
        $result = $this->Adv->advertsList(AdvertStatus::PLAY, AdvertType::ON_CATALOG, 10, 0);

        $this->assertIsArray($result);
    }

    public function test_advert()
    {
        $result = $this->Adv->advert(555);

        $this->assertIsObject($result);
        $this->assertObjectHasAttribute('advertId', $result);
        $this->assertIsArray($result->params);
    }

    public function test_cpm()
    {
        $result = $this->Adv->cpm(AdvertType::ON_SEARCH, 123456);

        $this->assertIsArray($result);
    }

    public function test_allCpm()
    {
        $result = $this->Adv->allCpm(AdvertType::ON_SEARCH, [123456, 7890]);

        $this->assertIsArray($result);
    }

    public function test_updateCpm()
    {
        $result = $this->Adv->updateCpm(123456, AdvertType::ON_HOME_RECOM, 123456, 123456);

        $this->assertFalse($result);
    }

    public function test_start()
    {
        $result = $this->Adv->start(123456);

        $this->assertFalse($result);
    }

    public function test_pause()
    {
        $result = $this->Adv->pause(123456);

        $this->assertFalse($result);
    }

    public function test_stop()
    {
        $result = $this->Adv->stop(123456);

        $this->assertFalse($result);
    }

    public function test_setActive()
    {
        $result = $this->Adv->setActive(123, 456, true);

        $this->assertFalse($result);
    }

    public function test_dailyBudget()
    {
        $this->Adv->dailyBudget(123, 500);

        $this->assertTrue($this->Adv->responseCode() == 400);
    }

    public function test_setIntervals()
    {
        $this->Adv->setIntervals(3344123, 275, [
            [ 'begin' => 3, 'end' => 5 ]
        ]);

        $this->assertTrue($this->Adv->responseCode() == 400);
    }

    public function test_nmActive()
    {
        $this->Adv->nmActive(456789, 275, [
            [ 'nm' => 2116745, 'active' => false ]
        ]);
        $this->assertTrue($this->Adv->responseCode() == 400);

        $this->expectException(InvalidArgumentException::class);
        $this->Adv->nmActive(456789, 275, array_fill(0, 50, [ 'nm' => 2116745, 'active' => false ]));
    }

    public function test_balance()
    {
        $result = $this->Adv->Finances()->balance();

        $this->assertIsObject($result);
        $this->assertObjectHasAttribute('balance', $result);
        $this->assertObjectHasAttribute('net', $result);
        $this->assertObjectHasAttribute('bonus', $result);
    }

    public function test_payments()
    {
        $result = $this->Adv->Finances()->payments(new \DateTime('2024-01-01'), new \DateTime('2024-01-31'));

        $this->assertIsArray($result);
    }

    public function test_costs()
    {
        $result = $this->Adv->Finances()->costs(new \DateTime('2024-01-01'), new \DateTime('2024-01-31'));

        $this->assertIsArray($result);
    }
}
