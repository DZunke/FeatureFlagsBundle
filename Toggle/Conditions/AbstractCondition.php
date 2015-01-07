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
     * @param Context $context
     * @return $this
     */
    public function setContext(Context $context)
    {
        $this->context = $context;

        return $this;
    }
}
