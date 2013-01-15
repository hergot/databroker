<?php

namespace hergot\databroker\DataAdapter;

class Parameter {
    
    /**
     * @var string
     */
    private $name;

    /**
     * @var bool
     */
    private $required;
    
    /**
     * @var mixed
     */
    private $defaultValue;
    
    /**
     * @var ValidatorInterface 
     */
    private $validator;

    /**
     * @var mixed
     */
    private $value;
    
    /**
     * @param string $name
     */
    public function __construct($name) {
        $this->name = $name;
        $this->required = false;
        $this->defaultValue = null;
        $this->validator = null;
        $this->value = null;
    }
    
    /**
     * @return string
     */
    public function getName() {
        return $this->name;
    }
    
    /**
     * @param bool $required
     * @return \hergot\databroker\DataAdapter\Parameter
     */
    public function setRequired($required) {
        $this->required = (bool)$required;
        return $this;
    }
    
    /**
     * @return bool
     */
    public function isRequired() {
        return $this->required;
    }
    
    /**
     * @param mixed $value
     * @return \hergot\databroker\DataAdapter\Parameter
     */
    public function setDefaultValue($value) {
        $this->defaultValue = $value;
        return $this;
    }
    
    /**
     * @return mixed
     */
    public function getDefaultValue() {
        return $this->defaultValue;
    }

    /**
     * @param \hergot\databroker\DataAdapter\ValidatorInterface $validator
     * @return \hergot\databroker\DataAdapter\Parameter
     */
    public function setValidator(ValidatorInterface $validator) {
        $this->validator = $validator;
        return $this;
    }
    
    /**
     * @return \hergot\databroker\DataAdapter\ValidatorInterface
     */
    public function getValidator() {
        return $this->validator;
    }
    
    /**
     * @param mixed $value
     * @return \hergot\databroker\DataAdapter\Parameter
     * @throws \InvalidArgumentException
     */
    public function setValue($value) {
        if ($this->validator instanceof ValidatorInterface 
                && !$this->validator->isValid($value)) {
            throw new \InvalidArgumentException('Setting value for parameter "' 
                    . $this->getName() . '" failed. Validator: "' . get_class($this->validator) . '"');
        }
        $this->value = $value;
        return $this;
    }
    
    /**
     * @return mixed
     */
    public function getValue() {
        return $this->value;
    }
}