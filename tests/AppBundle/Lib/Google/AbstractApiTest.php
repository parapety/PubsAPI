<?php

namespace AppBundle\Tests\Lib\Google;

use GuzzleHttp\Client;
use GuzzleHttp\Psr7\Request;
use AppBundle\Lib\Google\AbstractApi;

class AbstractApiTest extends \PHPUnit_Framework_TestCase
{
    /**
     * {@inheritdoc}
     */
    public function testGetWithExpectedResponseError()
    {
        $abstractApi = $this->getMockForAbstractClass(AbstractApi::class, [new Client(), 'fakeKey']);
        $respone = $this->invokeMethod($abstractApi, 'get', [new Request('GET', 'https://maps.googleapis.com/maps/api/NOT_VALID/json?address=aaa')]);

        $this->assertTrue(isset($respone['status_code']));
        if (isset($respone['status_code'])) {
            $this->assertTrue($respone['status_code'] == 404);
        }
    }

    private function invokeMethod(&$object, $methodName, array $parameters = array())
    {
        $reflection = new \ReflectionClass(get_class($object));
        $method = $reflection->getMethod($methodName);
        $method->setAccessible(true);

        return $method->invokeArgs($object, $parameters);
    }
}
