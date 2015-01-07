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
        $this->request = $request->getMasterRequest();
    }

    /**
     * @return bool
     */
    public function validate()
    {
        if ($this->request->cookies->has($this->config['cookie'])) {
            return (bool)$this->request->cookies->get($this->config['cookie']);
        }

        $value = (int)($this->generateRandomNumber() < $this->config['percentage']);
        setcookie(
            $this->config['cookie'],
            $value,
            time() + $this->config['lifetime']
        );

        return (bool)$value;
    }

    /**
     * {@inheritdoc }
     */
    public function setConfig($config)
    {
        parent::setConfig($config);

        if (!isset($this->config['percentage'])) {
            throw new \Exception('there must be a percentage set to use the condition');
        }

        if (!isset($this->config['cookie'])) {
            $this->config['cookie'] = self::BASIC_COOKIE_NAME;
        }

        if (!isset($this->config['lifetime'])) {
            $this->config['lifetime'] = self::BASIC_LIFETIME;
        }
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
        return 'Percentage';
    }

}
