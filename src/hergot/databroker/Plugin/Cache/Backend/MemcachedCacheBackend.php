<?php

namespace hergot\databroker\Plugin\Cache\Backend;

use hergot\databroker\Plugin\Cache\CacheBackendInterface;

class MemcachedCacheBackend implements CacheBackendInterface {
    
    /**
     * @var \Memcached
     */
    private $memcached;
    
    /**
     * @var Memcached\MemcachedServer
     */
    private $servers;
    
    public function __construct() {
        $this->servers = array();
    }
    
    /**
     * @param \hergot\databroker\Plugin\Cache\Backend\Memcached\MemcachedServer $server
     */
    public function addServer(Memcached\MemcachedServer $server) {
        $this->servers[] = $server;
    }
    
    /**
     * @param string $name
     */
    public function invalidate($name) {
        if (!$this->memcached instanceof \Memcached) {
            $this->connect();
        }
        $this->memcached->delete($name);
    }
    
    /**
     * @param string $name
     * @param mixed $defaultValue
     * @return string
     */
    public function read($name, $defaultValue = null) {
        if (!$this->memcached instanceof \Memcached) {
            $this->connect();
        }
        $result = $this->memcached->get($name);
        if ($result === false && $this->memcached->getResultCode() === \Memcached::RES_NOTFOUND) {
            return $defaultValue;
        } else {
            return $result;
        }
    }

    /**
     * @param string $name
     * @param string $value
     * @param integer $lifeTime
     * @throws \InvalidArgumentException
     */
    public function write($name, $value, $lifeTime) {
        if (!is_int($lifeTime) || $lifeTime < 0) {
            throw new \InvalidArgumentException('Lifetime must be integer greater than or equal to 0. Get "' . $lifeTime . '"');
        }
        if (!$this->memcached instanceof \Memcached) {
            $this->connect();
        }
        $this->memcached->set($name, $value, $lifeTime);
    }
    
    /**
     * @throws \RuntimeException
     */
    private function connect() {
        if (empty($this->servers)) {
            throw new \RuntimeException('Missing memcached server/s configuration');
        }
        $this->memcached = new \Memcached;
        foreach ($this->servers as $serverConfiguration) {
            $this->memcached->addServer($serverConfiguration->getHost(), 
                    $serverConfiguration->getPort(), $serverConfiguration->getWeight());
        }        
    }
}