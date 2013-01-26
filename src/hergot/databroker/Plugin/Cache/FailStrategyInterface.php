<?php

namespace hergot\databroker\Plugin\Cache;

use hergot\databroker\DataAdapter\DataAdapterInterface;

interface FailStrategyInterface {
    /**
     * @param \hergot\databroker\DataAdapter\DataAdapterInterface $dataAdapter
     * @param array $parameters
     * @param mixed $result
     * @param \Exception $exception
     * @param callable $cachedValueCallback
     * @return FailStrategyResult
     */
    public function getFailStrategyResult(DataAdapterInterface $dataAdapter, 
            array $parameters, $result, $cachedValueCallback, \Exception $exception=null);
}