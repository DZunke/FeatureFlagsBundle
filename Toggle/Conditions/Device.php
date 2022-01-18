<?php

namespace DZunke\FeatureFlagsBundle\Toggle\Conditions;

use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\RequestStack;

class Device extends AbstractCondition implements ConditionInterface
{

    /**
     * @var Request
     */
    private $request;

    /**
     * @param RequestStack $request
     */
    public function __construct(RequestStack $request)
    {
        $this->request = $request->getMainRequest();
    }

    /**
     * @param mixed $config
     * @param null  $argument
     * @return bool
     */
    public function validate($config, $argument = null)
    {
        if (!$this->validateConfig($config)) {
            return false;
        }

        $userAgent = $this->request->headers->get('User-Agent');

        if (is_null($argument)) {
            foreach ($config as $name => $regex) {
                if (preg_match($regex, $userAgent)) {
                    return true;
                }
            }
        } elseif (!is_null($argument) && array_key_exists($argument, $config)) {
            return (bool)preg_match($config[$argument], $userAgent);
        }

        return false;
    }

    /**
     * @param mixed $config
     * @return bool
     */
    private function validateConfig($config)
    {
        if (!is_array($config)) {
            return false;
        }

        return true;
    }

    /**
     * @return string
     */
    public function __toString()
    {
        return 'device';
    }

}
