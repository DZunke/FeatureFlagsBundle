<?php

namespace DZunke\FeatureFlagsBundle\Toggle\Conditions;

use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\RequestStack;

class Percentage extends AbstractCondition implements ConditionInterface
{

    const BASIC_COOKIE_NAME = '84a0b3f187a1d3bfefbb51d4b93074b1e5d9102a';

    const BASIC_PERCENTAGE = 100;

    const BASIC_LIFETIME = 86400;

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
     * @throws \Exception
     */
    public function validate($config, $argument = null)
    {
        $config = $this->formatConfig($config);

        if ($this->request->cookies->has($config['cookie'])) {
            return (bool)$this->request->cookies->get($config['cookie']);
        }

        $value = (int)($this->generateRandomNumber() < $config['percentage']);
        setcookie(
            $config['cookie'],
            $value,
            time() + $config['lifetime']
        );

        return (bool)$value;
    }

    private function formatConfig($config)
    {
        if (!isset($config['percentage'])) {
            throw new \Exception('there must be a percentage set to use the condition');
        }

        if (!isset($config['cookie'])) {
            $config['cookie'] = self::BASIC_COOKIE_NAME;
        }

        if (!isset($config['lifetime'])) {
            $config['lifetime'] = self::BASIC_LIFETIME;
        }

        return $config;
    }

    /**
     * @return int
     */
    private function generateRandomNumber()
    {
        return 100 * (mt_rand(0, mt_getrandmax() - 1) / mt_getrandmax());
    }

    /**
     * @return string
     */
    public function __toString()
    {
        return 'percentage';
    }

}
