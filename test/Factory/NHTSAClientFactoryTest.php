<?php
declare (strict_types = 1);
namespace ModusCreate\Test\Factory;

use PHPUnit\Framework\TestCase;
use ModusCreate\Factory\NHTSAClientFactory;
use GuzzleHttp\ClientInterface;

class NHTSAClientFactoryTest extends TestCase
{
    public function setUp()
    {
        parent::setUp();
    }

    public function testInstanceOfClientInterface()
    {
        $this->assertInstanceOf(ClientInterface::class, NHTSAClientFactory::newInstance());
    }

    public function testBaseUriSucceeds()
    {
        $uri = NHTSAClientFactory::newInstance()->getConfig('base_uri');
        $this->assertEquals('https', $uri->getScheme());
        $this->assertEquals('one.nhtsa.gov', $uri->getHost());
        $this->assertEquals('/webapi/api/', $uri->getPath());
    }
}
