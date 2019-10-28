<?php

namespace DZunke\FeatureFlagsBundle\Tests;

use DZunke\FeatureFlagsBundle\Context;
use PHPUnit\Framework\TestCase;

class ContextTest extends TestCase
{

    public function testGetAndSet()
    {
        $sut = new Context();

        $this->assertInstanceOf(Context::class, $sut->set('first', 'value'));
        $this->assertSame('value', $sut->get('first'));
    }

    public function testAll()
    {
        $sut = new Context();
        $sut->set('first', 'value');

        self::assertIsArray($sut->all());
        $this->assertSame(1, count($sut->all()));
    }

    public function testClear()
    {
        $sut = new Context();
        $sut->set('first', 'value');
        $sut->set('second', 'value');
        $sut->clear();

        $this->assertNull($sut->get('first'));
        $this->assertNull($sut->get('second'));
    }

}