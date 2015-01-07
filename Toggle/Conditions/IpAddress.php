<?php

namespace DZunke\FeatureFlagsBundle\Toggle\Conditions;

class IpAddress extends AbstractCondition implements ConditionInterface
{

    /**
     * @param mixed $config
     * @return bool
     */
    public function validate($config)
    {
        return in_array(
            $this->context->get('client_ip'),
            $config
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
