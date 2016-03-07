<?php

namespace DZunke\FeatureFlagsBundle\Tests\Toggle;

use DZunke\FeatureFlagsBundle\Toggle\ConditionBag;
use DZunke\FeatureFlagsBundle\Toggle\Conditions\ConditionInterface;
use DZunke\FeatureFlagsBundle\Toggle\Flag;
use PHPUnit_Framework_TestCase;
use InvalidArgumentException;

class FlagTest extends PHPUnit_Framework_TestCase
{

    public function testIsActiveReturnsBool()
    {
        $conditionBagMock = $this->getMock(ConditionBag::class);

        //For the addComponent method
        $conditionBagMock->method('has')->willReturn(true);

        //For the isActive method
        $conditionMock = $this->getMock(ConditionInterface::class);
        $conditionMock->method('validate')->will($this->onConsecutiveCalls(true, false));

        $conditionBagMock->method('get')->willReturn($conditionMock);

        $sut = new Flag('MySpecialFeature', $conditionBagMock, false);
        $sut->addCondition('ipaddress', ['192.168.1.1']);

        $this->assertTrue($sut->isActive());
        $this->assertFalse($sut->isActive());
    }

    public function testIsActiveReturnsDefaultIfEmptyArray()
    {
        $conditionBagMock = $this->getMock(ConditionBag::class);

        $sut = new Flag('MySpecialFeature', $conditionBagMock, true);

        $this->assertTrue($sut->isActive('argument'));
    }

    public function testGetConfigReturnsArray()
    {
        $conditionBagMock = $this->getMock(ConditionBag::class);

        $sut = new Flag('MySpecialFeature', $conditionBagMock, false);

        $this->assertInternalType('array', $sut->getConfig());
    }

    public function testAddConditionThrowsException()
    {
        $this->setExpectedException(InvalidArgumentException::class);

        $conditionBagMock = $this->getMock(ConditionBag::class);
        $conditionBagMock->method('has')
            ->willReturn(false);

        $sut = new Flag('MySpecialFeature', $conditionBagMock, true);
        $return = $sut->addCondition('throwexception', 'value');

        $this->assertInstanceOf(Flag::class, $return);
    }

    public function testToString()
    {
        $conditionBagMock = $this->getMock(ConditionBag::class);

        $sut = new Flag('MySpecialFeature', $conditionBagMock, true);

        $this->assertSame('MySpecialFeature', (string) $sut);
    }

}