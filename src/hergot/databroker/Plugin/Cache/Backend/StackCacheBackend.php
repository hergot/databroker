<?php

namespace hergot\databroker\Plugin\Cache\Backend;

use hergot\databroker\Plugin\Cache\CacheBackendInterface;

class StackCacheBackend implements CacheBackendInterface {

    /**
     * @var \hergot\databroker\Plugin\Cache\CacheBackendInterface[]
     */
    private $backend;
    
    /**
     * @param string $directory
     */
    public function __construct() {
        $this->backend = array();
    }

    /**
     * @param \hergot\databroker\Plugin\Cache\CacheBackendInterface $backend
     */
    public function addBackend(CacheBackendInterface $backend) {
        $this->backend[] = $backend;
    }

    /**
     * @param string $name
     */
    public function invalidate($name) {
        foreach ($this->backend as $backend) {
            $backend->invalidate($name);
        }
    }
    
    /**
     * @param string $name
     * @param mixed $defaultValue
     * @return string
     */
    public function read($name, $defaultValue = null) {
        foreach ($this->backend as $index => $backend) {
            $result = $backend->read($name, null);
            if ($result !== null) {
                $value = substr($result, 4);
                if ($index > 0) {
                    $lifeTime = unpack("N", substr($result, 0, 4))[1];
                    for ($i = 0; $i < $index; $i++) {
                        $this->backend[$i]->write($name, $value, $lifeTime);
                    }
                }
                return $value;
            }
        }
        return $defaultValue;
    }

    /**
     * @param string $name
     * @param string $value
     * @param integer $lifeTime
     * @throws \InvalidArgumentException
     */
    public function write($name, $value, $lifeTime) {
        foreach ($this->backend as $backend) {
            $backend->write($name, pack("N", $lifeTime) . $value, $lifeTime);
        }
    }

}