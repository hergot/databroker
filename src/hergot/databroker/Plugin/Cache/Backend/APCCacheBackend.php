<?php

namespace hergot\databroker\Plugin\Cache\Backend;

use hergot\databroker\Plugin\Cache\CacheBackendInterface;

class APCCacheBackend implements CacheBackendInterface {
    
    /**
     * @param string $name
     */
    public function invalidate($name) {
        apc_delete($name);
    }
    
    /**
     * @param string $name
     * @param mixed $defaultValue
     * @return string
     */
    public function read($name, $defaultValue = null) {
        $success = false;
        $result = apc_fetch($name, $success);
        return $success === false ? $defaultValue : $result;
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
        $ttl = $lifeTime - time();
        if ($ttl > 0) {
            apc_store($name, $value, $ttl);
        }
    }
}