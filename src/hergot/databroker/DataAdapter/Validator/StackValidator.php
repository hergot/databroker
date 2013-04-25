<?php

namespace hergot\databroker\DataAdapter\Validator;

use hergot\databroker\DataAdapter\ValidatorInterface;

class StackValidator implements ValidatorInterface {

    /**
     * @var array
     */
    private $validators;

    /**
     * Class constructor
     */
    public function __construct() {
        $this->validators = array();
    }

    /**
     * Add validator
     * 
     * @param \hergot\databroker\DataAdapter\ValidatorInterface $validator
     * @return \hergot\databroker\DataAdapter\Validator\StackValidator
     */
    public function addValidator(ValidatorInterface $validator) {
        $this->validators[] = $validator;
        return $this;
    }
    
    /**
     * Set validators
     * 
     * @param \Traversable|array $validators
     * @return \hergot\databroker\DataAdapter\Validator\StackValidator
     * @throws \InvalidArgumentException
     */
    public function setValidators($validators) {
        if (!$validators instanceof \Traversable && !is_array($validators)) {
            throw new \InvalidArgumentException('Validators must be array or implement Traversable interface');
        }
        if (is_array($validators)) {
            $this->validators = $validators;
        } else {
            $this->validators = array();
            foreach ($validators as $value) {
                $this->validators[] = $value;
            }
        }
        return $this;
    }
    
    /**
     * {@inheritdoc}
     */
    public function isValid($value) {
        foreach ($this->validators as $validator) {
            if ($validator->isValid($value) === false) {
                return false;
            }
        }
        return true;
    }

}