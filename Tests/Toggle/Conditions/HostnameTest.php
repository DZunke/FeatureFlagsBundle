<?php

namespace DZunke\FeatureFlagsBundle\Tests\Toggle\Condition;

use DZunke\FeatureFlagsBundle\Context;
use DZunke\FeatureFlagsBundle\Toggle\Conditions\AbstractCondition;
use DZunke\FeatureFlagsBundle\Toggle\Conditions\ConditionInterface;
use DZunke\FeatureFlagsBundle\Toggle\Conditions\Hostname;

class HostnameTest extends \PHPUnit_Framework_TestCase
{

    public function testItExtendsCorrectly()
    {
        $sut = new Hostname();

        $this->assertInstanceOf(AbstractCondition::class, $sut);
        $this->assertInstanceOf(ConditionInterface::class, $sut);
    }

    public function testItReturnsBoolean()
    {
        $contextMock = $this->getMock(Context::class);
        $contextMock->method('get')->willReturn('myhostname', 'thirdhostname');

        $sut = new Hostname();
        $sut->setContext($contextMock);

        $array = [
            'firsthostname',
            'secondhostname',
            'thirdhostname',
        ];

        $this->assertFalse($sut->validate($array));
        $this->assertTrue($sut->validate($array));
    }

    public function testToString()
    {
        $sut = new Hostname();

        $this->assertSame('hostname', (string) $sut);
    }

}