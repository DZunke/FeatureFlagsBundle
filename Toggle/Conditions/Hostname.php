<?php

namespace DZunke\FeatureFlagsBundle\Toggle\Conditions;

class Hostname extends AbstractCondition implements ConditionInterface
{

    /**
     * @return bool
     */
    public function validate()
    {
        return in_array(
            $this->context->get('hostname'),
            $this->config
        );
    }

    /**
     * @return string
     */
    public function __toString()
    {
        return 'Hostname';
    }

}
