<?php

namespace DZunke\FeatureFlagsBundle\Toggle\Conditions;

use DZunke\FeatureFlagsBundle\Context;

interface ConditionInterface
{
    public function __toString();

    public function setContext(Context $context);

    public function validate($config, $argument = null);

}
