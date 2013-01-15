<?php

namespace hergot\databroker\DataAdapter;

class ParameterCollection {
    
    /**
     * @var \hergot\databroker\DataAdapter\Parameter[]
     */
    private $parameters;
    
    public function __construct() {
        $this->parameters = array();
    }

    /**
     * @param \hergot\databroker\DataAdapter\Parameter $parameter
     * @throws \InvalidArgumentException
     */
    public function addParameter(Parameter $parameter) {
        if (isset($this->parameters[$parameter->getName()])) {
            throw new \InvalidArgumentException('Parameter with name "' . $parameter->getName() . '" already defined');
        }
        $this->parameters[$parameter->getName()] = $parameter;
    }
    
    /**
     * @return \hergot\databroker\DataAdapter\Parameter[]
     */
    public function getParameters() {
        return $this->parameters;
    }
    
    /**
     * @param string $name
     * @return \hergot\databroker\DataAdapter\Parameter|null
     */
    public function get($name) {
        return isset($this->parameters[$name]) ? $this->parameters[$name] : null;
    }
    
    /**
     * @return array
     */
    public function toArray() {
        $result = array();
        foreach ($this->parameters as $parameter) {
            $result[$parameter->getName()] = $parameter->getValue();
        }
        return $result;
    }
}