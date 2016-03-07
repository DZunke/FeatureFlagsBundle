<?php

namespace DZunke\FeatureFlagsBundle\Tests\Twig;

use DZunke\FeatureFlagsBundle\Toggle;
use DZunke\FeatureFlagsBundle\Twig\FeatureExtension;
use PHPUnit_Framework_TestCase;
use Twig_SimpleFunction;

class FeatureExtensionTest extends PHPUnit_Framework_TestCase
{

    public function testGetFunctions()
    {
        $toggleMock = $this->getMock(Toggle::class);

        $sut = new FeatureExtension($toggleMock);

        $this->assertSame(1, count($sut->getFunctions()));
        $this->assertInstanceOf(Twig_SimpleFunction::class, reset($sut->getFunctions()));
    }

    public function testGetName()
    {
        $toggleMock = $this->getMock(Toggle::class);

        $sut = new FeatureExtension($toggleMock);

        $this->assertSame('d_zunke_feature_extension', $sut->getName());
    }

}