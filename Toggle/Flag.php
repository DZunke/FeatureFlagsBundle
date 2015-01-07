<?php

namespace DZunke\FeatureFlagsBundle\Toggle;

use DZunke\FeatureFlagsBundle\Toggle\Conditions\ConditionInterface;

class Flag
{
    /**
     * @var string
     */
    private $name;

    /**
     * @var bool
     */
    private $active;

    /**
     * @var ConditionBag
     */
    private $conditions;

    /**
     * @var array
     */
    private $config = [];

    /**
     * @param              $name
     * @param ConditionBag $conditions
     * @param bool         $defaultState
     */
    public function __construct($name, ConditionBag $conditions, $defaultState = true)
    {
        $this->name       = $name;
        $this->conditions = $conditions;
        $this->active     = (bool)$defaultState;
    }

    /**
     * @return string
     */
    public function __toString()
    {
        return $this->name;
    }

    /**
     * @param $name
     * @param $config
     * @return $this
     */
    public function addCondition($name, $config)
    {
        if (!$this->conditions->has($name)) {
            throw new \InvalidArgumentException(
                sprintf('condition with name "%s" does not exists', $name)
            );
        }

        $this->config[$name] = $config;

        return $this;
    }

    /**
     * @return array
     */
    public function getConfig()
    {
        return $this->config;
    }

    /**
     * @return bool
     */
    public function isActive()
    {
        $actual = $this->active;

        if (empty($this->config)) {
            return $actual;
        }

        foreach ($this->config as $condition => $config) {

            if (!$this->conditions->has($condition)) {
                continue;
            }

            $validate = $this->conditions->get($condition)->validate($config);

            if ($actual === true && $validate === false) {
                return false;
            } elseif ($actual === false && $validate === false) {
                return false;
            }

        }

        return true;
    }

}
