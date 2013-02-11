<?php

namespace hergot\databroker\DataAdapter\Validator;

class IntegerValidator extends NumericValidator {

    /**
     * {@inheritdoc}
     */
    protected function checkType($value) {
        if (!is_integer($value)) {
            throw new \InvalidArgumentException('Value must be integer. Get: "' . $value . '"');
        }
    }
    
}