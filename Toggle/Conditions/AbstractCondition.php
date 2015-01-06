<?php

namespace DZunke\FeatureFlagsBundle\Toggle\Conditions;

use DZunke\FeatureFlagsBundle\Context;

abstract class AbstractCondition implements ConditionInterface
{
    /**
     * @var Context
     */
    protected $context;

    /**
     * @var array
     */
    protected $config = [];

    /**
     * @param array $config
     * @return $this
     * @throws \Exception
     */
    public function setConfig($config)
    {
        if (!is_array($config) || empty($config)) {
            throw new \Exception('invalid configuration for condition');
        }

        $this->config = $config;

        return $this;
    }

    /**
     * @param Context $context
     * @return $this
     */
    public function setContext(Context $context)
    {
        $this->context = $context;

        return $this;
    }

    public function shutdown()
    {
        return null;
    }
}
