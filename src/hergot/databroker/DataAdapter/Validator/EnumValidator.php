<?php

namespace hergot\databroker\DataAdapter\Validator;

use hergot\databroker\DataAdapter\ValidatorInterface;

class EnumValidator implements ValidatorInterface {

    /**
     * @var array
     */
    private $allowedValues;
    
    /**
     * @var bool
     */
    private $strict;

    /**
     * Class constructor
     * 
     * @param bool $strict
     */
    public function __construct($strict=false) {
        $this->allowedValues = array();
        $this->strict = (bool)$strict;
    }

    /**
     * Set allowed values
     * 
     * @param array|\Traversable $allowedValues
     * @return \hergot\databroker\DataAdapter\Validator\EnumValidator
     * @throws \InvalidArgumentException
     */
    public function setAllowedValues($allowedValues) {
        if (!$allowedValues instanceof \Traversable && !is_array($allowedValues)) {
            throw new \InvalidArgumentException('Allowed values must be array or implement Traversable interface');
        }
        if (is_array($allowedValues)) {
            $this->allowedValues = $allowedValues;
        } else {
            $this->allowedValues = array();
            foreach ($allowedValues as $value) {
                $this->allowedValues[] = $value;
            }
        }
        return $this;
    }

    /**
     * Add allowed value
     * 
     * @param mixed $allowedValue
     * @return \hergot\databroker\DataAdapter\Validator\EnumValidator
     */
    public function addAllowedValue($allowedValue) {
        $this->allowedValues[] = $allowedValue;
        return $this;
    }

    /**
     * {@inheritdoc}
     */
    public function isValid($value) {
        return in_array($value, $this->allowedValues, $this->strict);
    }

}