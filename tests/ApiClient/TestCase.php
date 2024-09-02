<?php

namespace Dakword\WBSeller\Tests\ApiClient;

use Dakword\WBSeller\API\Endpoint\Adv;
use Dakword\WBSeller\API\Endpoint\Analytics;
use Dakword\WBSeller\API\Endpoint\Content;
use Dakword\WBSeller\API\Endpoint\Feedbacks;
use Dakword\WBSeller\API\Endpoint\Marketplace;
use Dakword\WBSeller\API\Endpoint\Prices;
use Dakword\WBSeller\API\Endpoint\Questions;
use Dakword\WBSeller\API\Endpoint\Recommends;
use Dakword\WBSeller\API\Endpoint\Statistics;
use Dakword\WBSeller\API\Endpoint\Subpoint\DBS;
use Dakword\WBSeller\API\Endpoint\Subpoint\News;
use Dakword\WBSeller\API\Endpoint\Subpoint\Passes;
use Dakword\WBSeller\API\Endpoint\Subpoint\Tags;
use Dakword\WBSeller\API\Endpoint\Subpoint\Templates;
use Dakword\WBSeller\API\Endpoint\Subpoint\Warehouses;
use Dakword\WBSeller\Exception\ApiTimeRestrictionsException;
use Dakword\WBSeller\Tests\TestCase as BaseTestCase;

abstract class TestCase extends BaseTestCase
{

    protected function Adv(): Adv
    {
        $this->skipIfNoKeyAPI();
        return $this->API()->Adv();
    }

    protected function Analytics(): Analytics
    {
        $this->skipIfNoKeyAPI();
        return $this->API()->Analytics();
    }

    protected function Content(): Content
    {
        $this->skipIfNoKeyAPI();
        return $this->API()->Content();
    }

    protected function ContentNews(): News
    {
        $this->skipIfNoKeyAPI();
        return $this->API()->Content()->News();
    }

    protected function ContentTags(): Tags
    {
        $this->skipIfNoKeyAPI();
        return $this->API()->Content()->Tags();
    }

    protected function Feedbacks(): Feedbacks
    {
        $this->skipIfNoKeyAPI();
        return $this->API()->Feedbacks();
    }

    protected function FeedbacksTemplates(): Templates
    {
        $this->skipIfNoKeyAPI();
        return $this->API()->Feedbacks()->Templates();
    }

    protected function Marketplace(): Marketplace
    {
        $this->skipIfNoKeyAPI();
        return $this->API()->Marketplace();
    }

    protected function MarketplaceDBS(): DBS
    {
        $this->skipIfNoKeyAPI();
        return $this->API()->Marketplace()->DBS();
    }

    protected function MarketplacePasses(): Passes
    {
        $this->skipIfNoKeyAPI();
        return $this->API()->Marketplace()->Passes();
    }

    protected function MarketplaceWarehouses(): Warehouses
    {
        $this->skipIfNoKeyAPI();
        return $this->API()->Marketplace()->Warehouses();
    }

    protected function Prices(): Prices
    {
        $this->skipIfNoKeyAPI();
        return $this->API()->Prices();
    }

    protected function Questions(): Questions
    {
        $this->skipIfNoKeyAPI();
        return $this->API()->Questions();
    }

    protected function QuestionsTemplates(): Templates
    {
        $this->skipIfNoKeyAPI();
        return $this->API()->Questions()->Templates();
    }

    protected function Recommends(): Recommends
    {
        $this->skipIfNoKeyAPI();
        return $this->API()->Recommends();
    }

    protected function Statistics(): Statistics
    {
        $this->skipIfNoKeyAPI();
        return $this->API()->Statistics();
    }

    protected function getRealNms($limit = 10)
    {
        try {
            $result = $this->Content()
                ->getCardsList('', $limit);
        } catch (ApiTimeRestrictionsException $exc) {
            $this->markTestSkipped($exc->getMessage());
        }

        if (count($result->cards) == 0) {
            $this->markTestSkipped('No cards in account');
        }

        return $list = array_column($result->cards, 'nmID');
    }

}
