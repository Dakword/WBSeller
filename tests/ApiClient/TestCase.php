<?php

namespace Dakword\WBSeller\Tests\ApiClient;

use Dakword\WBSeller\Tests\TestCase as BaseTestCase;
use Dakword\WBSeller\API;
use Dakword\WBSeller\API\Endpoint\{
    Content,
    Marketplace,
    Prices,
    Promo,
    Statistics
};

abstract class TestCase extends BaseTestCase
{

    protected function Content(): Content
    {
        $this->skipIfNoKeyAPI();
        return $this->API()->Content();
    }

    protected function Marketplace(): Marketplace
    {
        $this->skipIfNoKeyAPI();
        return $this->API()->Marketplace();
    }

    protected function Prices(): Prices
    {
        $this->skipIfNoKeyAPI();
        return $this->API()->Prices();
    }

    protected function Promo(): Promo
    {
        $this->skipIfNoKeyAPI();
        return $this->API()->Promo();
    }

    protected function Statistics(): Statistics
    {
        $this->skipIfNoKeySTAT();
        return $this->API()->Statistics();
    }

}
