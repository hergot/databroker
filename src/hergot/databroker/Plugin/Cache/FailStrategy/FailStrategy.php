<?php

namespace hergot\databroker\Plugin\Cache\FailStrategy;

use hergot\databroker\Plugin\Cache\FailStrategyInterface;

abstract class FailStrategy implements FailStrategyInterface {

    /**
     * @var mixed
     */
    private $returnValue;
    
    /**
     * @var integer
     */
    private $refreshTime;
    
    /**
     * @param integer $refreshTime
     * @throws \InvalidArgumentException
     */
    public function setRefreshTime($refreshTime) {
        if (!is_int($refreshTime) || $refreshTime < 0) {
            throw new \InvalidArgumentException('Refresh time must be integer and bigger or equal to zero. "' . $refreshTime . '"');
        }
        $this->refreshTime = $refreshTime;
    }
    
    /**
     * @return integer
     */
    public function getRefreshTime() {
        return $this->refreshTime;
    }
    
    /**
     * @return mixed
     */
    public function getReturnValue() {
        return $this->returnValue;
    }

    /**
     * @param mixed $returnValue
     */
    public function setReturnValue($returnValue) {
        $this->returnValue = $returnValue;
    }

}