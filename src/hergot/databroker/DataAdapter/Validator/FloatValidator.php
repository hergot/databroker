<?php

namespace hergot\databroker\DataAdapter\Validator;

class FloatValidator extends NumericValidator {

    /**
     * {@inheritdoc}
     */
    protected function checkType($value) {
        if (!is_float($value)) {
            throw new \InvalidArgumentException('Value must be float. Get: "' . $value . '"');
        }
    }

}