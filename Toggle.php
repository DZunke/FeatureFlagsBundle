<?php

namespace DZunke\FeatureFlagsBundle;

use DZunke\FeatureFlagsBundle\Toggle\Flag;

class Toggle
{

    /**
     * @var bool
     */
    private $defaultState = true;

    /**
     * @var Flag[]
     */
    private $flags = [];

    /**
     * @param bool $defaultState
     * @return $this
     */
    public function setDefaultState($defaultState)
    {
        $this->defaultState = (bool)$defaultState;

        return $this;
    }

    /**
     * @param Flag $flag
     * @return $this
     */
    public function addFlag(Flag $flag)
    {
        $this->flags[(string)$flag] = $flag;

        return $this;
    }

    /**
     * @param $name
     * @return Flag|null
     */
    public function getFlag($name)
    {
        if (isset($this->flags[$name])) {
            return $this->flags[$name];
        }

        return null;
    }

    /**
     * @return Toggle\Flag[]
     */
    public function getFlags()
    {
        return $this->flags;
    }

    /**
     * @param string $flag
     * @param array  $arguments
     * @return bool
     */
    public function isActive($flag, $arguments = null)
    {
        if (!isset($this->flags[$flag])) {
            return $this->defaultState;
        }

        return $this->flags[$flag]->isActive($arguments);
    }

}
