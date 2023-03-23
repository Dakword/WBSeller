<?php

namespace Dakword\WBSeller\Tests\ApiClient;

use Dakword\WBSeller\API\Endpoint\Questions;
use Dakword\WBSeller\Tests\ApiClient\TestCase;

class QuestionsTest extends TestCase
{

    private $Questions;
    
    public function setUp(): void
    {
        parent::setUp();
        
        $this->Questions = $this->Questions();
    }

    public function test_Class()
    {
        $this->assertInstanceOf(Questions::class, $this->API()->Questions());
    }

    public function test_unansweredCount()
    {
        $result = $this->Questions->unansweredCount();

        $this->assertFalse($result->error);

        if(!$result->error) {
            $this->assertObjectHasAttribute('data', $result);
            $this->assertObjectHasAttribute('countUnanswered', $result->data);
            $this->assertObjectHasAttribute('countUnansweredToday', $result->data);
        }
    }

    public function test_hasNew()
    {
        $result = $this->Questions->hasNew();

        $this->assertFalse($result->error);

        if(!$result->error) {
            $this->assertObjectHasAttribute('hasNewQuestions', $result->data);
            $this->assertObjectHasAttribute('hasNewFeedbacks', $result->data);
        }
    }

    public function test_productRating()
    {
        $result = $this->Questions->productRating();

        $this->assertFalse($result->error);

        if(!$result->error) {
            $this->assertObjectHasAttribute('data', $result);
            $this->assertObjectHasAttribute('products', $result->data);
            $this->assertIsArray($result->data->products);
        }
    }

    public function test_list()
    {
        $result = $this->Questions->list();

        $this->assertFalse($result->error);

        if(!$result->error) {
            $this->assertObjectHasAttribute('data', $result);
            $this->assertObjectHasAttribute('countUnanswered', $result->data);
            $this->assertObjectHasAttribute('countArchive', $result->data);
            $this->assertObjectHasAttribute('questions', $result->data);
            $this->assertIsArray($result->data->questions);
        }
    }

    public function test_xlsReport()
    {
        $result = $this->Questions->xlsReport();

        $this->assertFalse($result->error);

        if(!$result->error) {
            $this->assertObjectHasAttribute('data', $result);
            $this->assertObjectHasAttribute('file', $result->data);
            $this->assertObjectHasAttribute('fileName', $result->data);
            $this->assertObjectHasAttribute('contentType', $result->data);
        }
    }

    public function test_changeViewed()
    {
        $result = $this->Questions->changeViewed('xxl', true);
        $response = $this->Questions->response();

        $this->assertFalse($result);
        $this->assertTrue($response->error);
        $this->assertEquals('Не найдены вопросы по поставщику', $response->errorText);
    }

    public function test_sendAnswer()
    {
        $result = $this->Questions->sendAnswer('xxl', 'OK!');
        $response = $this->Questions->response();

        $this->assertFalse($result);
        $this->assertTrue($response->error);
        $this->assertEquals('Не найдены вопросы по поставщику', $response->errorText);
    }

    public function test_reject()
    {
        $result = $this->Questions->reject('xxl', 'answer');
        $response = $this->Questions->response();

        $this->assertFalse($result);
        $this->assertTrue($response->error);
        $this->assertEquals('Не найдены вопросы по поставщику', $response->errorText);
    }

}