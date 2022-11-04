<?php

namespace Dakword\WBSeller\Tests;

use PHPUnit\Framework\TestCase as PHPUnitTestCase;
use Dakword\WBSeller\API;
use Dakword\WBSeller\Endpoints\{
    Content,
    Marketplace,
    Prices,
    Promo,
    Statistics
};

class TestCase extends PHPUnitTestCase
{
    private string $apiKey;
    private string $statKey;

    public function setUp(): void
    {
        if (file_exists(__DIR__ . '/../../#KEYS.php')) {
            list($this->apiKey, $this->statKey) = include __DIR__ . '/../../#KEYS.php';
        } else {
            $this->apiKey = getenv('APIKEY');
            $this->statKey = getenv('STATKEY');
        }
    }

    protected function API(): API
    {
        return new API([
            'apikey' => $this->apiKey,
            'statkey' => $this->statKey,
        ]);
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

    protected function Statistics(): Statistics
    {
        $this->skipIfNoKeySTAT();
        return $this->API()->Statistics();
    }

    private function skipIfNoKeyAPI(): void
    {
        if (empty($this->apiKey)) {
            $this->markTestSkipped();
        }
    }

    private function skipIfNoKeySTAT(): void
    {
        if (empty($this->statKey)) {
            $this->markTestSkipped();
        }
    }

}
