<?php

namespace DZunke\FeatureFlagsBundle\Tests;

use DZunke\FeatureFlagsBundle\Toggle;
use PHPUnit_Framework_TestCase;

class ToggleTest extends PHPUnit_Framework_TestCase
{

    public function testDefaultStateIsTrue()
    {
        $sut = new Toggle();

        $this->assertTrue($sut->isActive('test'));
    }

    public function testDefaultState()
    {
        $sut = new Toggle();

        $this->assertInstanceOf(Toggle::class, $sut->setDefaultState(true));
        $this->assertInstanceOf(Toggle::class, $sut->setDefaultState(false));
    }

    public function testGetAndAddFlag()
    {
        $flagMock = $this->getMock(Toggle\Flag::class, [], ['MySpecialFeature', new Toggle\ConditionBag(), true]);
        $flagMock->method('__toString')->willReturn('test_flag');

        $sut = new Toggle();

        $this->assertInstanceOf(Toggle::class, $sut->addFlag($flagMock));
        $this->assertNull($sut->getFlag('nothingShouldBeReturned'));
        $this->assertSame($flagMock, $sut->getFlag('test_flag'));
    }

    public function testIsActive()
    {
        $flagMock = $this->getMock(Toggle\Flag::class, [], ['MySpecialFeature', new Toggle\ConditionBag(), true]);
        $flagMock->method('__toString')->willReturn('MySpecialFeature');
        $flagMock->method('isActive')->willReturn(true, false);

        $sut = new Toggle();
        $sut->addFlag($flagMock);

        $this->assertTrue($sut->isActive('MySpecialFeature'));
        $this->assertFalse($sut->isActive('MySpecialFeature'));
    }

}