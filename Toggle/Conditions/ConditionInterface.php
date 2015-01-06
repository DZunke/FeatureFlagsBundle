<?php

namespace DZunke\FeatureFlagsBundle\Toggle\Conditions;

use DZunke\FeatureFlagsBundle\Context;

interface ConditionInterface
{
    public function __toString();

    public function setContext(Context $context);

    public function setConfig($config);

    public function validate();

    public function shutdown();
}
