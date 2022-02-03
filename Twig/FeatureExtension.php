<?php

namespace DZunke\FeatureFlagsBundle\Twig;

use DZunke\FeatureFlagsBundle\Toggle;
use Twig\Extension\AbstractExtension;
use Twig\TwigFunction;

class FeatureExtension extends AbstractExtension
{

    /**
     * @var Toggle
     */
    protected $toggle;

    /**
     * @param Toggle $toggle
     */
    public function __construct(Toggle $toggle)
    {
        $this->toggle = $toggle;
    }

    /**
     * @return TwigFunction[]
     */
    public function getFunctions(): array
    {
        return [
            new TwigFunction('has_feature', [$this, 'checkFeature'], ['is_safe' => ['html']]),
        ];
    }

    /**
     * @param  string $name
     * @param  array  $arguments
     * @return bool
     */
    public function checkFeature($name, $arguments = null)
    {
        return $this->toggle->isActive($name, $arguments);
    }

    public function getName()
    {
        return 'd_zunke_feature_extension';
    }
}
