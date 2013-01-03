<?php

namespace hergot\databroker\Plugin;

interface ValueExceptionInterface {
    
    /**
     * @return mixed return value which will be passed as fetch result
     */
    public function getValue();
};