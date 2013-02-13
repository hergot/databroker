<?php

namespace hergot\databroker\DataAdapter\Validator;

use hergot\databroker\DataAdapter\ValidatorInterface;

class InterfaceValidator implements ValidatorInterface {

    /**
     * @var string
     */
    private $interface;

    /**
     * Class constructor
     */
    public function __construct() {
        $this->interface = null;
    }

    /**
     * Set interface
     * 
     * @param string $interface
     * @return \hergot\databroker\DataAdapter\Validator\InterfaceValidator
     * @throws \InvalidArgumentException
     */
    public function setInterface($interface) {
        if (!is_string($interface)) {
            throw new \InvalidArgumentException('Interface must be string. Get: "' . $interface . '"');
        }
        $this->interface = $interface;
        return $this;
    }

    /**
     * {@inheritdoc}
     */
    public function isValid($value) {
        if (!is_object($value)) {
            return false;
        }
        
        if ($this->interface !== null && !($value instanceof $this->interface)) {
            return false;
        }

        return true;
    }

}