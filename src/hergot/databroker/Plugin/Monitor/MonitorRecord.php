<?php

namespace hergot\databroker\Plugin\Monitor;

class MonitorRecord {

    /**
     * @var string
     */
    private $adapter;

    /**
     * @var array
     */
    private $parameters;
    
    /**
     * @var float
     */
    private $duration;

    /**
     * @var mixed
     */
    private $result;
    
    /**
     * @var \Exception
     */
    private $exception;
    
    /**
     * @var float
     */
    private $time;

    /**
     * Monitor record constructor
     * 
     * @param string $adapter
     * @param array $parameters
     * @param float $duration
     * @param mixed $result
     * @param \Exception $exception
     */
    function __construct($adapter, array $parameters, $duration, $result, \Exception $exception=null) {
        $this->adapter = $adapter;
        $this->parameters = $parameters;
        $this->duration = (float)$duration;
        $this->result = $result;
        $this->exception = $exception;
        $this->time = microtime(true);
    }
    
    /**
     * Retrieve record time
     * 
     * @return float
     */
    public function getTime() {
        return $this->time;
    }

    /**
     * Retrieve adapter
     * 
     * @return string
     */
    public function getAdapter() {
        return $this->adapter;
    }

    /**
     * Set adapter
     * 
     * @param string $adapter
     */
    public function setAdapter($adapter) {
        $this->adapter = $adapter;
    }

    /**
     * Retrieve adapter parameters
     * 
     * @return array
     */
    public function getParameters() {
        return $this->parameters;
    }

    /**
     * Set adapter parameters
     * 
     * @param array $parameters
     */
    public function setParameters(array $parameters=array()) {
        $this->parameters = $parameters;
    }

    /**
     * Retrieve duration
     * 
     * @return float
     */
    public function getDuration() {
        return $this->duration;
    }

    /**
     * Set duration
     * 
     * @param float $duration
     */
    public function setDuration($duration) {
        $this->duration = (float)$duration;
    }

    /**
     * Retrieve result
     * 
     * @return mixed
     */
    public function getResult() {
        return $this->result;
    }

    /**
     * Set result
     * 
     * @param mixed $result
     */
    public function setResult($result) {
        $this->result = $result;
    }

    /**
     * Retrieve record exception
     * 
     * @return \Exception|null
     */
    public function getException() {
        return $this->exception;
    }

    /**
     * Set record exception
     * 
     * @param \Exception $exception
     */
    public function setException(\Exception $exception=null) {
        $this->exception = $exception;
    }

}