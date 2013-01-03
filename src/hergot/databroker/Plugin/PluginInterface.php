<?php

namespace hergot\databroker\Plugin;

use hergot\databroker\DataAdapter\DataAdapterInterface;

interface PluginInterface {
    /**
     * @param \hergot\databroker\DataAdapter\DataAdapterInterface $dataAdapter
     * @param array $parameters
     * @param mixed $result
     */
    public function runBeforeExecute(DataAdapterInterface $dataAdapter, array $parameters, $result);
    /**
     * @param \hergot\databroker\DataAdapter\DataAdapterInterface $dataAdapter
     * @param array $parameters
     * @param mixed $result
     * @param \Exception $exception
     */
    public function runAfterExecute(DataAdapterInterface $dataAdapter, array $parameters, $result, \Exception $exception=null);
}