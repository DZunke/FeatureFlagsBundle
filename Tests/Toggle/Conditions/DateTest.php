<?php

namespace DZunke\FeatureFlagsBundle\Tests\Toggle\Condition;

use DZunke\FeatureFlagsBundle\Context;
use DZunke\FeatureFlagsBundle\Toggle\Conditions\AbstractCondition;
use DZunke\FeatureFlagsBundle\Toggle\Conditions\ConditionInterface;
use DZunke\FeatureFlagsBundle\Toggle\Conditions\Date;
use PHPUnit_Framework_TestCase;
use ReflectionClass;

class DateTest extends PHPUnit_Framework_TestCase
{
    /**
     * @var string
     */
    const CURRENT_DATE = "2016-09-02T00:00:00Z";

    /**
     * @var string
     */
    const FUTURE_DATE1 = "2020-12-25T12:00:00Z";

    /**
     * @var string
     */
    const FUTURE_DATE2 = "2017-0-0T00:00:00Z";

    /**
     * @var string
     */
    const PAST_DATE1 = "1970-01-01T00:00:00Z";

    /**
     * @var string
     */
    const PAST_DATE2 = "2014-01-01T00:00:00Z";

    /**
     * Returns an instance of Date with a known "current" date.
     *
     * @return Date
     */
    public function getInstanceOfDate()
    {
        $contextMock = $this->getMock(Context::class);

        $date = new Date();
        $date->setContext($contextMock);

        $reflection = new ReflectionClass($date);
        $reflection_property = $reflection->getProperty("currentDate");
        $reflection_property->setAccessible(true);
        $reflection_property->setValue($date, self::CURRENT_DATE);

        return $date;
    }

    public function testItExtendsCorrectly()
    {
        $sut = $this->getInstanceOfDate();

        $this->assertInstanceOf(AbstractCondition::class, $sut);
        $this->assertInstanceOf(ConditionInterface::class, $sut);
    }

    public function testItReturnsTrueWithoutStartOrEndDate()
    {
        $config = [];
        $sut = $this->getInstanceOfDate();
        $this->assertTrue($sut->validate($config));
    }

    public function testItReturnsTrueWithPastStartDate()
    {
        $sut = $this->getInstanceOfDate();
        $this->assertTrue($sut->validate([
            'start_date' => self::PAST_DATE1,
        ]));
        $this->assertTrue($sut->validate([
            'start_date' => self::PAST_DATE2,
        ]));
        $this->assertFalse($sut->validate([
            'start_date' => self::FUTURE_DATE1,
        ]));
        $this->assertFalse($sut->validate([
            'start_date' => self::FUTURE_DATE2,
        ]));
    }

    public function testItReturnsTrueWithFutureEndDate()
    {
        $sut = $this->getInstanceOfDate();
        $this->assertTrue($sut->validate([
            'end_date' => self::FUTURE_DATE1,
        ]));
        $this->assertTrue($sut->validate([
            'end_date' => self::FUTURE_DATE2,
        ]));
        $this->assertFalse($sut->validate([
            'end_date' => self::PAST_DATE1,
        ]));
        $this->assertFalse($sut->validate([
            'end_date' => self::PAST_DATE2,
        ]));
    }

    public function testItReturnsTrueWhenBetweenPastStartAndFutureEndDate()
    {
        $sut = $this->getInstanceOfDate();
        $this->assertTrue($sut->validate([
            'start_date' => self::PAST_DATE1,
            'end_date' => self::FUTURE_DATE2,
        ]));
        $this->assertFalse($sut->validate([
            'start_date' => self::FUTURE_DATE2,
            'end_date' => self::PAST_DATE1,
        ]));
        $this->assertTrue($sut->validate([
            'start_date' => self::PAST_DATE1,
            'end_date' => self::FUTURE_DATE2,
        ]));
        $this->assertFalse($sut->validate([
            'start_date' => self::FUTURE_DATE2,
            'end_date' => self::PAST_DATE1,
        ]));
    }

    public function testToString()
    {
        $sut = $this->getInstanceOfDate();

        $this->assertSame('date', (string) $sut);
    }
}
