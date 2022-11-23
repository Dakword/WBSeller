<?php

namespace Dakword\WBSeller\Tests;

use PHPUnit\Framework\TestCase as PHPUnitTestCase;
use Dakword\WBSeller\API;
use Dakword\WBSeller\Query;
use Dakword\WBSeller\API\Endpoint\{
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

    protected function Query(): Query
    {
        return new Query($this->API());
    }

    protected function skipIfNoKeyAPI(): void
    {
        if (empty($this->apiKey)) {
            $this->markTestSkipped();
        }
    }

    protected function skipIfNoKeySTAT(): void
    {
        if (empty($this->statKey)) {
            $this->markTestSkipped();
        }
    }

}
