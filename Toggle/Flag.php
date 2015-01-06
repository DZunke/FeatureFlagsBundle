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
     * @var ConditionInterface[]
     */
    private $conditions = [];

    /**
     * @var array
     */
    private $conditionsConfig = [];

    /**
     * @param string $name
     * @param bool   $defaultState
     */
    public function __construct($name, $defaultState)
    {
        $this->name   = $name;
        $this->active = (bool)$defaultState;
    }

    /**
     * @return string
     */
    public function __toString()
    {
        return $this->name;
    }

    /**
     * @param ConditionInterface $condition
     * @param  mixed             $config
     * @return $this
     */
    public function addCondition(ConditionInterface $condition, $config)
    {
        $this->conditions[(string)$condition]       = $condition;
        $this->conditionsConfig[(string)$condition] = $config;

        return $this;
    }

    /**
     * @param string $name
     * @return ConditionInterface|null
     */
    public function getCondition($name)
    {
        if (isset($this->conditions[$name])) {
            return $this->conditions[$name];
        }

        return null;
    }

    /**
     * @return Conditions\ConditionInterface[]
     */
    public function getConditions()
    {
        return $this->conditions;
    }

    /**
     * @return bool
     */
    public function isActive()
    {
        $actual = $this->active;

        if (empty($this->conditions)) {
            return $actual;
        }

        foreach ($this->conditions as $condition) {

            if (isset($this->conditionsConfig[(string)$condition])) {
                $condition->setConfig($this->conditionsConfig[(string)$condition]);
            }

            $validate = $condition->validate();

            if ($actual === true && $validate === false) {
                return false;
            } elseif ($actual === false && $validate === false) {
                return false;
            }

        }

        return true;
    }

}
