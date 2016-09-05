<?php

namespace DZunke\FeatureFlagsBundle\Tests\Toggle\Condition;

use DZunke\FeatureFlagsBundle\Toggle\Conditions\AbstractCondition;
use DZunke\FeatureFlagsBundle\Toggle\Conditions\ConditionInterface;
use DZunke\FeatureFlagsBundle\Toggle\Conditions\Percentage;
use Exception;
use PHPUnit_Framework_TestCase;
use Symfony\Component\DependencyInjection\ParameterBag\ParameterBag;
use Symfony\Component\HttpFoundation\Request;

class PercentageTest extends PHPUnit_Framework_TestCase
{

    public function testItExtendsCorrectly()
    {
        $request = $this->getMock(Request::class);

        $sut = new Percentage($request);

        $this->assertInstanceOf(AbstractCondition::class, $sut);
        $this->assertInstanceOf(ConditionInterface::class, $sut);
    }

    public function testItThrowsExceptionWhenPercentageIsNotSet()
    {
        $this->setExpectedException(Exception::class);

        $request = $this->getMock(Request::class);

        $sut = new Percentage($request);
        $sut->validate([], 'nothing');
    }

    public function testItReturnsTrueWhenCookieIsAlreadySet()
    {
        $parameterBagMock = $this->getMock(ParameterBag::class);
        $parameterBagMock->method('has')->willReturn(true);
        $parameterBagMock->method('get')->willReturn(1);

        $requestMock = $this->getMock(Request::class);
        $requestMock->cookies = $parameterBagMock;

        $sut = new Percentage($requestMock);
        $this->assertTrue($sut->validate([
            'percentage' => 798,
        ]));
    }

    public function testItReturnsBoolWhenCookieIsNotSet()
    {
        $parameterBagMock = $this->getMock(ParameterBag::class);
        $parameterBagMock->method('has')->willReturn(true);

        $requestMock = $this->getMock(Request::class);
        $requestMock->cookies = $parameterBagMock;

        $sut = new Percentage($requestMock);
        $this->assertInternalType('bool', $sut->validate(['percentage' => 3]));
    }

    public function testToString()
    {
        $sut = new Percentage($this->getMock(Request::class));

        $this->assertSame('percentage', (string) $sut);
    }

}