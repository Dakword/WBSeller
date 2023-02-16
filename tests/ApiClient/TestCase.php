<?php

namespace Dakword\WBSeller\Tests\ApiClient;

use Dakword\WBSeller\Tests\TestCase as BaseTestCase;
use Dakword\WBSeller\API\Endpoint\{
    Adv,
    Content,
    Marketplace,
    Prices,
    Promo,
    Recommendations,
    Statistics
};

abstract class TestCase extends BaseTestCase
{

    protected function Adv(): Adv
    {
        $this->skipIfNoKeyADV();
        return $this->API()->Adv();
    }

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

    protected function Recommendations(): Recommendations
    {
        $this->skipIfNoKeyAPI();
        return $this->API()->Recommendations();
    }

    protected function Statistics(): Statistics
    {
        $this->skipIfNoKeySTAT();
        return $this->API()->Statistics();
    }

}
