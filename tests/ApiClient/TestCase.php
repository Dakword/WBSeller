<?php

namespace Dakword\WBSeller\Tests\ApiClient;

use Dakword\WBSeller\Tests\TestCase as BaseTestCase;
use Dakword\WBSeller\API\Endpoint\{
    Adv,
    Content,
    Feedbacks,
    Marketplace,
    Prices,
    Promo,
    Questions,
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

    protected function Feedbacks(): Feedbacks
    {
        $this->skipIfNoKeyAPI();
        return $this->API()->Feedbacks();
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

    protected function Questions(): Questions
    {
        $this->skipIfNoKeyAPI();
        return $this->API()->Questions();
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
