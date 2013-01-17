<?php

namespace hergot\databroker\Plugin\Cache;

class FailStrategy {

    /**
     * @var string
     */
    const RETURN_TYPE_VALUE = 'value';

    /**
     * @var string
     */
    const RETURN_TYPE_CACHED_VALUE = 'cachedValue';
    
    /**
     * @var string
     */
    private $returnType;

    /**
     * @var mixed
     */
    private $returnValue;

    /**
     * @var integer
     */
    private $refreshTime;

    /**
     * @param string $returnType
     * @param integer $refreshTime
     * @param mixed $returnValue
     */
    public function __construct($returnType, $refreshTime, $returnValue=null) {
        $this->setReturnType($returnType);
        $this->setRefreshTime($refreshTime);
        $this->returnValue = $returnValue;
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

    /**
     * @return integer
     */
    public function getRefreshTime() {
        return $this->refreshTime;
    }

    /**
     * @param integer $refreshTime
     * @throws \InvalidArgumentException
     */
    public function setRefreshTime($refreshTime) {
        if (!is_int($refreshTime) || $refreshTime <= 0) {
            throw new \InvalidArgumentException('Refresh time must be integer and bigger than 0. Got: "' . $refreshTime . '"');
        }
        $this->refreshTime = $refreshTime;
    }
    
    /**
     * @return string
     */
    public function getReturnType() {
        return $this->returnType;
    }

    /**
     * @param string $returnType
     * @throws \InvalidArgumentException
     */
    public function setReturnType($returnType) {
        $allowedValues = array(self::RETURN_TYPE_CACHED_VALUE, self::RETURN_TYPE_VALUE);
        if (!is_string($returnType) || !in_array($returnType, $allowedValues)) {
            throw new \InvalidArgumentException('Return type must be string and one of the following: "' 
                    . implode('", "', $allowedValues) . '". Got: "' . $returnType . '"');
        }
        $this->returnType = $returnType;
    }


}