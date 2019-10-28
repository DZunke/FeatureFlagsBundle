<?php

namespace DZunke\FeatureFlagsBundle\Tests\DependencyInjection;

use DZunke\FeatureFlagsBundle\DependencyInjection\DZunkeFeatureFlagsExtension;
use PHPUnit\Framework\TestCase;
use Symfony\Component\DependencyInjection\ContainerBuilder;

final class ConfigurationTest extends TestCase
{
    public function testDefaultConfigurationCouldBeLoaded()
    {
        $container = new ContainerBuilder();
        $extension = new DZunkeFeatureFlagsExtension();
        $extension->load(['d_zunke_feature_flags' => ['flags' => ['FooFeature' => ['default' => false]]]], $container);

        $config = $extension->getProcessedConfigs();

        self::assertSame(
            [['flags' => ['FooFeature' => ['default' => false, 'conditions_config' => []]], 'default' => true]],
            $config
        );
    }
}