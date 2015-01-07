<?php

namespace DZunke\FeatureFlagsBundle\Twig;

use DZunke\FeatureFlagsBundle\Toggle;

class FeatureExtension extends \Twig_Extension
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

    public function getFunctions()
    {
        return [
            new \Twig_SimpleFunction('has_feature', [$this, 'checkFeature'], ['is_safe' => ['html']]),
        ];
    }

    /**
     * @param string $name
     * @return bool
     */
    public function checkFeature($name)
    {
        return $this->toggle->isActive($name);
    }

    public function getName()
    {
        return 'd_zunke_feature_extension';
    }
}
