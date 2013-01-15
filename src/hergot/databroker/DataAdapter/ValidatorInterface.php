<?php

namespace hergot\databroker\DataAdapter;

interface ValidatorInterface {
    /**
     * Check if value is valid
     * 
     * @param bool $value
     */
    public function isValid($value);
}