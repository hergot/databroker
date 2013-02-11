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
        if (!is_numeric($min)) {
            throw new \InvalidArgumentException('Minimal value must be numeric. Get: "' . $min . '"');
        }
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
        if (!is_numeric($max)) {
            throw new \InvalidArgumentException('Maximal value must be numeric. Get: "' . $max . '"');
        }
        $this->max = $max;
        return $this;
    }

    /**
     * {@inheritdoc}
     */
    public function isValid($value) {
        if (!is_numeric($value)) {
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

}