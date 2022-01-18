<?php

namespace DZunke\FeatureFlagsBundle\Tests\Toggle\Conditions;

use DZunke\FeatureFlagsBundle\Toggle\Conditions\AbstractCondition;
use DZunke\FeatureFlagsBundle\Toggle\Conditions\ConditionInterface;
use DZunke\FeatureFlagsBundle\Toggle\Conditions\Percentage;
use Exception;
use PHPUnit\Framework\TestCase;
use Symfony\Component\DependencyInjection\ParameterBag\ParameterBag;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\RequestStack;

class PercentageTest extends TestCase
{

    public function testItExtendsCorrectly()
    {
        $requestStackMock = $this->createMock(RequestStack::class);

        $sut = new Percentage($requestStackMock);

        $this->assertInstanceOf(AbstractCondition::class, $sut);
        $this->assertInstanceOf(ConditionInterface::class, $sut);
    }

    public function testItThrowsExceptionWhenPercentageIsNotSet()
    {
        $this->expectException(Exception::class);

        $requestStackMock = $this->createMock(RequestStack::class);

        $sut = new Percentage($requestStackMock);
        $sut->validate([], 'nothing');
    }

    public function testItReturnsTrueWhenCookieIsAlreadySet()
    {
        $parameterBagMock = $this->createMock(ParameterBag::class);
        $parameterBagMock->method('has')->willReturn(true);
        $parameterBagMock->method('get')->willReturn(1);

        $requestMock = $this->createMock(Request::class);
        $requestMock->cookies = $parameterBagMock;

        $requestStackMock = $this->createMock(RequestStack::class);
        $requestStackMock->method('getMainRequest')->willReturn($requestMock);

        $sut = new Percentage($requestStackMock);
        $this->assertTrue($sut->validate([
            'percentage' => 798,
        ]));
    }

    public function testItReturnsBoolWhenCookieIsNotSet()
    {
        $parameterBagMock = $this->createMock(ParameterBag::class);
        $parameterBagMock->method('has')->willReturn(true);

        $requestMock = $this->createMock(Request::class);
        $requestMock->cookies = $parameterBagMock;

        $requestStackMock = $this->createMock(RequestStack::class);
        $requestStackMock->method('getMainRequest')->willReturn($requestMock);

        $sut = new Percentage($requestStackMock);
        self::assertIsBool($sut->validate(['percentage' => 3]));
    }

    public function testToString()
    {
        $requestStackMock = $this->createMock(RequestStack::class);

        $sut = new Percentage($requestStackMock);

        $this->assertSame('percentage', (string) $sut);
    }

}
