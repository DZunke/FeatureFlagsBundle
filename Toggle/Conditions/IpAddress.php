<?php

namespace DZunke\FeatureFlagsBundle\Toggle\Conditions;

class IpAddress extends AbstractCondition implements ConditionInterface
{

    /**
     * @return bool
     */
    public function validate()
    {
        return in_array(
            $this->context->get('client_ip'),
            $this->config
        );
    }

    /**
     * @return string
     */
    public function __toString()
    {
        return 'IpAddress';
    }

}
