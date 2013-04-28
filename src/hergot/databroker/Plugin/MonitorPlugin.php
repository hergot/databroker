<?php

namespace hergot\databroker\Plugin;

use hergot\databroker\DataAdapter\DataAdapterInterface;

class MonitorPlugin implements PluginInterface
{

    /**
     * @var Monitor\MonitorRecordWriterInterface
     */
    private $writer;

    /**
     * @var float[]
     */
    private $startTime;

    public function __construct(Monitor\MonitorRecordWriterInterface $writer)
    {
        $this->startTime = array();
        $this->writer = $writer;
    }

    /**
     * @param \hergot\databroker\DataAdapter\DataAdapterInterface $dataAdapter
     * @param array $parameters
     * @param mixed $result
     * @return mixed
     */
    public function runAfterExecute(DataAdapterInterface $dataAdapter, array $parameters, $result, \Exception $exception = null)
    {
        $hash = $this->getHash($dataAdapter, $parameters);
        if (empty($this->startTime) || empty($this->startTime[$hash])) {
            throw new \RuntimeException('Method runBeforeExecute not called');
        }
        $startTime = array_pop($this->startTime[$hash]);
        $duration = microtime(true) - $startTime;
        $record = new Monitor\MonitorRecord(get_class($dataAdapter), 
                unserialize(serialize($parameters)), $duration, 
                unserialize(serialize($result)), 
                $exception instanceof \Exception ? $exception : $exception);
        $this->writer->write($record);
    }

    /**
     * @param \hergot\databroker\DataAdapter\DataAdapterInterface $dataAdapter
     * @param array $parameters
     * @param mixed $result
     * @return mixed
     */
    public function runBeforeExecute(DataAdapterInterface $dataAdapter, array $parameters, $result)
    {
        $hash = $this->getHash($dataAdapter, $parameters);
        if (!isset($this->startTime[$hash])) {
            $this->startTime[$hash] = array();
        }
        array_push($this->startTime[$hash], microtime(true));
    }

    /**
     * Retrieve hash based on input parameters
     * 
     * @param \hergot\databroker\DataAdapter\DataAdapterInterface $dataAdapter
     * @param array $parameters
     * @return string
     */
    private function getHash(DataAdapterInterface $dataAdapter, array $parameters)
    {
        return md5(get_class($dataAdapter) . serialize($parameters));
    }

}