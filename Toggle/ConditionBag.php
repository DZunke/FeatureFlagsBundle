<?php

namespace DZunke\FeatureFlagsBundle\Toggle;

use DZunke\FeatureFlagsBundle\Toggle\Conditions\ConditionInterface;

class ConditionBag implements \IteratorAggregate, \Countable
{

    /**
     * @var ConditionInterface[]
     */
    protected $conditions = [];

    /**
     * @return \ArrayIterator
     */
    public function getIterator(): \Traversable
    {
        return new \ArrayIterator($this->conditions);
    }

    /**
     * @return int
     */
    public function count(): int
    {
        return count($this->conditions);
    }

    /**
     * @return Conditions\ConditionInterface[]
     */
    public function all()
    {
        return $this->conditions;
    }

    /**
     * @param array $conditions
     * @return $this
     */
    public function add(array $conditions)
    {
        foreach ($conditions as $condition) {
            $this->set((string)$condition, $condition);
        }

        return $this;
    }

    /**
     * @param string             $name
     * @param ConditionInterface $condition
     * @return $this
     */
    public function set($name, ConditionInterface $condition)
    {
        $this->conditions[$name] = $condition;

        return $this;
    }

    /**
     * @param $name
     * @return ConditionInterface|null
     */
    public function get($name)
    {
        return isset($this->conditions[$name]) ? $this->conditions[$name] : null;
    }

    /**
     * @return array
     */
    public function keys()
    {
        return array_keys($this->conditions);
    }

    /**
     * @param string $name
     * @return bool
     */
    public function has($name)
    {
        return array_key_exists($name, $this->conditions);
    }

    /**
     * @param string $name
     * @return $this
     */
    public function remove($name)
    {
        unset($this->conditions[$name]);

        return $this;
    }
}
