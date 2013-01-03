<?php

namespace hergot\databroker\Plugin\Cache;

interface CacheBackendInterface {
    public function read($name, $defaultValue=null);
    public function write($name, $value, $lifeTime);
    public function invalidate($name);
}