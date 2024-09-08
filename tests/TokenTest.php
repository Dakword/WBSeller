<?php

namespace Dakword\WBSeller\Tests;

use Dakword\WBSeller\APIToken;
use Dakword\WBSeller\Exception\WBSellerException;
use Dakword\WBSeller\Tests\ApiClient\TestCase;
use DateTime;

class TokenTest extends TestCase
{
    private $testToken = [
        'exp' => 1722882872,
        's' => 1073741832,
        'sid' => '22e91535-959d-4978-95d0-44c9f0d93d3c',
        'oid' => 3931956,
        't' => false,
        'access' => [3 => 'Цены и скидки'],
    ];

    private function APIToken()
    {
        return new APIToken(
            base64_encode(json_encode([
                'alg' => 'ES256',
                'typ' => 'JWT',
                'kid' => '20231225v1',
            ]))
            . '.' . base64_encode(json_encode($this->testToken))
            . '.' . 'hash'
        );
    }

    public function test_APIToken()
    {
        $token = $this->APIToken();
        $this->assertInstanceOf(APIToken::class, $token);
    }
    public function test_APITokenException1()
    {
        $this->expectException(WBSellerException::class);
        new APIToken('1.2.3.4');
    }
    public function test_APITokenException2()
    {
        $this->expectException(WBSellerException::class);
        new APIToken('111.22222.33333');
    }

    public function test_getPayload()
    {
        $token = $this->APIToken();
        $payload = $token->getPayload();

        $this->assertObjectNotHasAttribute('id', $payload);
        $this->assertObjectHasAttribute('exp', $payload);
        $this->assertEquals($this->testToken['sid'], $token->sellerUUID());
    }

    public function test_expireDate()
    {
        $token = $this->APIToken();

        $this->assertInstanceOf('DateTime', $token->expireDate());
        $this->assertEquals(
            (new DateTime())->setTimestamp($this->testToken['exp'])->format('Y-m-d H:i:s'),
            $token->expireDate()->format('Y-m-d H:i:s')
        );
    }

    public function test_isExpired()
    {
        $token = $this->APIToken();

        $this->assertTrue($token->isExpired());
    }

    public function test_isTest()
    {
        $token = $this->APIToken();

        $this->assertFalse($token->isTest());
    }

    public function test_isReadOnly()
    {
        $token = $this->APIToken();

        $this->assertTrue($token->isReadOnly());
    }

    public function test_sellerId()
    {
        $token = $this->APIToken();

        $this->assertEquals($this->testToken['oid'], $token->sellerId());
    }

    public function test_sellerUUID()
    {
        $token = $this->APIToken();

        $this->assertEquals($this->testToken['sid'], $token->sellerUUID());
    }

    public function test_accessList()
    {
        $token = $this->APIToken();

        $this->assertEquals($this->testToken['access'], $token->accessList());
    }

    public function test_accessTo()
    {
        $token = $this->APIToken();

        $this->assertTrue($token->accessTo('prices'));
        $this->assertTrue($token->accessTo('common'));
        $this->assertFalse($token->accessTo('chat'));
        $this->assertFalse($token->accessTo('sex'));
    }
}
