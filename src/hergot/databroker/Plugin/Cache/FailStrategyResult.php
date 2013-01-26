<?php

namespace hergot\databroker\Plugin\Cache;

class FailStrategyResult {

    /**
     * @var integer
     */
    private $refreshTimeInSeconds;

    /**
     * @var mixed
     */
    private $value;

    /**
     * @param integer $refreshTime
     * @param mixed $value
     */
    function __construct($refreshTime, $value) {
        $this->setRefreshTime($refreshTime);
        $this->value = $value;
    }

    /**
     * @return integer
     */
    public function getRefreshTime() {
        return $this->refreshTimeInSeconds;
    }

    /**
     * @param integer $refreshTimeInSeconds
     * @throws \InvalidArgumentException
     */
    public function setRefreshTime($refreshTimeInSeconds) {
        if (!is_int($refreshTimeInSeconds) || $refreshTimeInSeconds < 0) {
            throw new \InvalidArgumentException('Refresh time must be integer bigger or equal to zero. "' . $refreshTimeInSeconds . '"');
        }
        $this->refreshTimeInSeconds = $refreshTimeInSeconds;
    }

    /**
     * @return mixed
     */
    public function getValue() {
        return $this->value;
    }

    /**
     * @param mixed $value
     */
    public function setValue($value) {
        $this->value = $value;
    }

}