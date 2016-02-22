<?php

namespace DZunke\FeatureFlagsBundle\Tests\Toggle\Condition;

use DZunke\FeatureFlagsBundle\Toggle\Conditions\AbstractCondition;
use DZunke\FeatureFlagsBundle\Toggle\Conditions\ConditionInterface;
use DZunke\FeatureFlagsBundle\Toggle\Conditions\Percentage;
use Exception;
use PHPUnit_Framework_TestCase;
use Symfony\Component\DependencyInjection\ParameterBag\ParameterBag;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\RequestStack;

class PercentageTest extends PHPUnit_Framework_TestCase
{

    public function testItExtendsCorrectly()
    {
        $requestStackMock = $this->getMock(RequestStack::class);

        $sut = new Percentage($requestStackMock);

        $this->assertInstanceOf(AbstractCondition::class, $sut);
        $this->assertInstanceOf(ConditionInterface::class, $sut);
    }

    public function testItThrowsExceptionWhenPercentageIsNotSet()
    {
        $this->setExpectedException(Exception::class);

        $requestStackMock = $this->getMock(RequestStack::class);

        $sut = new Percentage($requestStackMock);
        $sut->validate([], 'nothing');
    }

    public function testItReturnsTrueWhenCookieIsAlreadySet()
    {
        $parameterBagMock = $this->getMock(ParameterBag::class);
        $parameterBagMock->method('has')->willReturn(true);
        $parameterBagMock->method('get')->willReturn(1);

        $requestMock = $this->getMock(Request::class);
        $requestMock->cookies = $parameterBagMock;

        $requestStackMock = $this->getMock(RequestStack::class);
        $requestStackMock->method('getMasterRequest')->willReturn($requestMock);

        $sut = new Percentage($requestStackMock);
        $this->assertTrue($sut->validate([
            'percentage' => 666,
        ]));
    }

    public function testItReturnsBoolWhenCookieIsNotSet()
    {
        $parameterBagMock = $this->getMock(ParameterBag::class);
        $parameterBagMock->method('has')->willReturn(true);

        $requestMock = $this->getMock(Request::class);
        $requestMock->cookies = $parameterBagMock;

        $requestStackMock = $this->getMock(RequestStack::class);
        $requestStackMock->method('getMasterRequest')->willReturn($requestMock);

        $sut = new Percentage($requestStackMock);
        $this->assertInternalType('bool', $sut->validate(['percentage' => 3]));
    }

    public function testToStringMethod()
    {
        $requestStackMock = $this->getMock(RequestStack::class);

        $sut = new Percentage($requestStackMock);

        $this->assertSame('percentage', (string) $sut);
    }

}