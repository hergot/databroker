<?php

namespace hergot\databroker\Plugin\Cache\FailStrategy;

use hergot\databroker\Plugin\Cache\FailStrategyResult;
use hergot\databroker\DataAdapter\DataAdapterInterface;

class ConstValueFailStrategy extends FailStrategy {
    
    /**
     * @param integer $refreshTime
     * @param mixed $returnValue
     */
    public function __construct($refreshTime, $returnValue=null) {
        $this->setRefreshTime($refreshTime);
        $this->setReturnValue($returnValue);
    }

    /**
     * {@inheritdoc}
     */
    public function getFailStrategyResult(DataAdapterInterface $dataAdapter, 
            array $parameters, $result, $cachedValueCallback, \Exception $exception=null) {
        return new FailStrategyResult($this->getRefreshTime(), $this->getReturnValue());
    }
}