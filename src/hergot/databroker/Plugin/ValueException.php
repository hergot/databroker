<?php

namespace hergot\databroker\Plugin;

class ValueException extends \Exception implements ValueExceptionInterface {

    private $value;
    
    public function __construct($message, $code, $previous, $value=null) {
        parent::__construct($message, $code, $previous);
        $this->value = $value;
    }
    
    public function getValue() {
        return $this->value;
    }
}