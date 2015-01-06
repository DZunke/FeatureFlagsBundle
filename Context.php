<?php

namespace DZunke\FeatureFlagsBundle;

class Context
{

    /**
     * @var array
     */
    private $context = [];

    /**
     * @param string $name
     * @param mixed  $value
     * @return $this
     */
    public function set($name, $value)
    {
        $this->context[(string)$name] = $value;

        return $this;
    }

    /**
     * @param string $name
     * @return null
     */
    public function get($name)
    {
        return isset($this->context[(string)$name]) ? $this->context[(string)$name] : null;
    }

    /**
     * @return array
     */
    public function all()
    {
        return $this->context;
    }

    /**
     * @return $this
     */
    public function clear()
    {
        $this->context = [];

        return $this;
    }

}
