<?php

namespace DZunke\FeatureFlagsBundle\Toggle\Conditions;

class Hostname extends AbstractCondition implements ConditionInterface
{

    /**
     * @param mixed $config
     * @param null  $argument
     * @return bool
     */
    public function validate($config, $argument = null)
    {
        return in_array(
            $this->context->get('hostname'),
            $config
        );
    }

    /**
     * @return string
     */
    public function __toString()
    {
        return 'hostname';
    }

}
