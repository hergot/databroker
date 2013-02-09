<?php

namespace hergot\databroker\DataAdapter\Validator;

use hergot\databroker\DataAdapter\ValidatorInterface;

class StringValidator implements ValidatorInterface {

    /**
     * @var integer
     */
    private $minLength;

    /**
     * @var integer
     */
    private $maxLength;

    /**
     * Class constructor
     */
    public function __construct() {
        $this->minLength = null;
        $this->maxLength = null;
    }

    /**
     * Set minimal string length
     * 
     * @param integer $minLength
     * @return \hergot\databroker\DataAdapter\Validator\StringValidator
     * @throws \InvalidArgumentException
     */
    public function setMinLength($minLength) {
        if (!is_integer($minLength) || $minLength < 0) {
            throw new \InvalidArgumentException('Minimal length must be numeric and greather or equal to zero. Get: "' . $minLength . '"');
        }
        $this->minLength = $minLength;
        return $this;
    }

    /**
     * Set maximal string length
     * 
     * @param integer $minLength
     * @return \hergot\databroker\DataAdapter\Validator\StringValidator
     * @throws \InvalidArgumentException
     */
    public function setMaxLength($maxLength) {
        if (!is_integer($maxLength) || $maxLength < 0) {
            throw new \InvalidArgumentException('Maximal length must be numeric and greather or equal to zero. Get: "' . $maxLength . '"');
        }
        $this->maxLength = $maxLength;
        return $this;
    }

    /**
     * {@inheritdoc}
     */
    public function isValid($value) {
        if (!is_string($value)) {
            return false;
        }

        if ($this->minLength !== null && strlen($value) < $this->minLength) {
            return false;
        }

        if ($this->maxLength !== null && strlen($value) > $this->maxLength) {
            return false;
        }

        return true;
    }

}