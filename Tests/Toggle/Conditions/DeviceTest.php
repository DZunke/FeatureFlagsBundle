<?php

namespace DZunke\FeatureFlagsBundle\Tests\Toggle\Condition;

use DZunke\FeatureFlagsBundle\Toggle\Conditions\AbstractCondition;
use DZunke\FeatureFlagsBundle\Toggle\Conditions\ConditionInterface;
use DZunke\FeatureFlagsBundle\Toggle\Conditions\Device;
use PHPUnit_Framework_TestCase;
use Symfony\Component\HttpFoundation\HeaderBag;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\RequestStack;
use PHPUnit_Framework_MockObject_MockObject;

class DeviceTest extends PHPUnit_Framework_TestCase
{

    /**
     * @var RequestStack|PHPUnit_Framework_MockObject_MockObject
     */
    private $requestStackMock;

    public function setUp()
    {
        $this->requestStackMock = $this->createMock(RequestStack::class);
    }

    public function testItExtendsCorrectly()
    {
        $sut = new Device($this->requestStackMock);

        $this->assertInstanceOf(AbstractCondition::class, $sut);
        $this->assertInstanceOf(ConditionInterface::class, $sut);
    }

    public function testIsReturnsFalseItConfigIsIncorrect()
    {
        $sut = new Device($this->requestStackMock);

        $this->assertFalse($sut->validate(null));
    }

    public function testItReturnsFalseIfUserAgentDoesNotExistInArray()
    {
        $headerBagMock = $this->createMock(HeaderBag::class);
        $headerBagMock->method('get')->with('User-Agent')->willReturn('Custom-User-Agent');

        $requestMock = $this->createMock(Request::class);
        $requestMock->headers = $headerBagMock;

        $this->requestStackMock->method('getMasterRequest')->willReturn($requestMock);

        $sut = new Device($this->requestStackMock);

        $this->assertFalse($sut->validate([], null));
    }

    public function testItReturnsBoolWhenCallingValidateWithoutArgument()
    {
        $headerBagMock = $this->createMock(HeaderBag::class);
        $headerBagMock->method('get')->with('User-Agent')->will($this->onConsecutiveCalls('Matched-User-Agent', 'Random-User-Agent'));

        $requestMock = $this->createMock(Request::class);
        $requestMock->headers = $headerBagMock;

        $this->requestStackMock->method('getMasterRequest')->willReturn($requestMock);

        $sut = new Device($this->requestStackMock);

        $array = [
            'first-agents' => '/^Custom-Agent-1|Custom-Agent-2|Custom-Agent-3|Custom-Agent-4$/',
            'second-agents' => '/^Custom-Agent-5|Custom-Agent-6|Custom-Agent-7|Matched-User-Agent$/'
        ];

        $this->assertTrue($sut->validate($array, null));
        $this->assertFalse($sut->validate($array, null));
    }

    public function testItReturnsBoolWhenCallingValidateWithArgument()
    {
        $headerBagMock = $this->createMock(HeaderBag::class);
        $headerBagMock->method('get')->with('User-Agent')->willReturn('Custom-Agent-3', 'Random-User-Agent');

        $requestMock = $this->createMock(Request::class);
        $requestMock->headers = $headerBagMock;

        $this->requestStackMock->method('getMasterRequest')->willReturn($requestMock);

        $sut = new Device($this->requestStackMock);

        $array = [
            'first-agents' => '/^Custom-Agent-1|Custom-Agent-2|Custom-Agent-3|Custom-Agent-4$/',
            'second-agents' => '/^Custom-Agent-5|Custom-Agent-6|Custom-Agent-7|Matched-User-Agent$/'
        ];

        $this->assertTrue($sut->validate($array, 'first-agents'));
        $this->assertFalse($sut->validate($array, 'second-agents'));
    }

    public function testToString()
    {
        $sut = new Device($this->requestStackMock);

        $this->assertSame('device', (string) $sut);
    }

}