<?php

namespace hergot\databroker\DataAdapter\Validator;

use hergot\databroker\DataAdapter\ValidatorInterface;

class NumericValidator implements ValidatorInterface {

    /**
     * @var numeric
     */
    private $min;

    /**
     * @var numeric
     */
    private $max;

    /**
     * Class constructor
     */
    public function __construct() {
        $this->min = null;
        $this->max = null;
    }

    /**
     * Set minimal value
     * 
     * @param numeric $min
     * @return \hergot\databroker\DataAdapter\Validator\NumericValidator
     * @throws \InvalidArgumentException
     */
    public function setMin($min) {
        $this->checkType($min);
        $this->min = $min;
        return $this;
    }

    /**
     * Set maximal value
     * 
     * @param numeric $max
     * @return \hergot\databroker\DataAdapter\Validator\NumericValidator
     * @throws \InvalidArgumentException
     */
    public function setMax($max) {
        $this->checkType($max);
        $this->max = $max;
        return $this;
    }

    /**
     * {@inheritdoc}
     */
    public function isValid($value) {
        try {
            $this->checkType($value);
        } catch (\InvalidArgumentException $e) {
            return false;
        }
        
        if ($this->min !== null && $value < $this->min) {
            return false;
        }

        if ($this->max !== null && $value > $this->max) {
            return false;
        }

        return true;
    }
    
    /**
     * Check value type
     * 
     * @param mixed $value
     * @throws \InvalidArgumentException
     */
    protected function checkType($value) {
        if (!is_numeric($value)) {
            throw new \InvalidArgumentException('Value must be numeric. Get: "' . $value . '"');
        }
    }

}