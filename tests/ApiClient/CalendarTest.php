<?php

namespace Dakword\WBSeller\Tests\ApiClient;

use Dakword\WBSeller\API\Endpoint\Calendar;
use Dakword\WBSeller\Tests\ApiClient\TestCase;

/**
 * @coversDefaultClass \Dakword\WBSeller\API\Endpoint\Calendar
 */
class CalendarTest extends TestCase
{

    public function test_Class()
    {
        $this->assertInstanceOf(Calendar::class, $this->API()->Calendar());
    }

    /**
     * @covers ::promotions()
     */
    public function test_promotions()
    {
        $result = $this->API()->Calendar()
            ->promotions(new \DateTime('2024-07-01'), new \DateTime());

        $this->assertIsArray($result->data->promotions);
    }

    /**
     * @covers ::promotionsDetails()
     */
    public function test_promotionDetails()
    {
        $calendar = $this->API()->Calendar();
        $promotions = $calendar->promotions(
            new \DateTime('2024-09-01'),
            new \DateTime(),
            false
        );

        if($promotions->data->promotions ?? false) {
            $last = array_pop($promotions->data->promotions);
            $result = $calendar->promotionDetails($last->id);
            $this->assertIsArray($result->data->promotions ?? false);
        }
    }

    /**
     * @covers ::promotionNomenclatures()
     */
    public function test_promotionNomenclatures()
    {
        $calendar = $this->API()->Calendar();
        $promotions = $calendar->promotions(
            new \DateTime('2024-09-01'),
            new \DateTime(),
            true
        );

        if($promotions->data->promotions ?? false) {
            $actions = array_filter($promotions->data->promotions, fn($action) => $action->type != 'auto');
            $action = array_pop($actions);

            if($action) {
                $result = $calendar->promotionNomenclatures($action->id);
                $this->assertIsArray($result->data->nomenclatures ?? false);
            }
        }
    }

}
