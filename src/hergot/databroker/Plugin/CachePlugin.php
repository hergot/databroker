<?php

namespace hergot\databroker\Plugin;

use hergot\databroker\DataAdapter\DataAdapterInterface;

class CachePlugin implements PluginInterface {
    
    /**
     * @var \hergot\databroker\Plugin\Cache\CacheSetting[]
     */
    private $adapterCacheSettings;

    /**
     * @var array
     */
    private $dataLoadedFromCache;
    
    public function __construct() {
        $this->adapterCacheSettings = array();
        $this->dataLoadedFromCache = array();
    }

    /**
     * Setup cache configuration for data adapter/s
     * 
     * @param string $dataAdapterMask will be compared with data adapter class name
     * @return \hergot\databroker\Plugin\Cache\CacheSetting
     */
    public function setup($dataAdapterMask) {
        $cacheSetting = new Cache\CacheSetting();
        $this->adapterCacheSettings[$dataAdapterMask] = $cacheSetting;
        return $cacheSetting;
    }
    
    /**
     * @param \hergot\databroker\DataAdapter\DataAdapterInterface $dataAdapter
     * @param array $parameters
     * @param mixed $result
     * @return mixed
     */
    public function runAfterExecute(DataAdapterInterface $dataAdapter, array $parameters, $result, \Exception $exception=null) {
        if (!$this->isAdapterCacheable($dataAdapter)) {
            return $result;
        }
        
        if (isset($this->dataLoadedFromCache[md5(get_class($dataAdapter) . serialize($parameters))])
            && $this->dataLoadedFromCache[md5(get_class($dataAdapter) . serialize($parameters))] === true) {
            unset($this->dataLoadedFromCache[md5(get_class($dataAdapter) . serialize($parameters))]);
            return $result;
        }

        $settings = $this->getSettingsForAdapter($dataAdapter);
        $refreshTime = $settings->getRefreshTime();
        $lifeTime = $settings->getLifeTime();
        $data = pack("N", $refreshTime === null 
                ? $lifeTime + time() : $refreshTime + time()) . serialize($result);
        $this->saveCacheData($dataAdapter, $parameters, $data);
        return $result;
    }

    /**
     * @param \hergot\databroker\DataAdapter\DataAdapterInterface $dataAdapter
     * @param array $parameters
     * @param mixed $result
     * @return mixed
     */
    public function runBeforeExecute(DataAdapterInterface $dataAdapter, array $parameters, $result) {
        if (!$this->isAdapterCacheable($dataAdapter)) {
            return $result;
        }
        
        $cachedData = $this->getCachedData($dataAdapter, $parameters);
        if ($cachedData === null) {
            return $result;
        }
        
        $refreshTime = unpack("N", substr($cachedData, 0, 4))[1];
        if ($refreshTime < time()) {
            return $result;
        }
        $data = unserialize(substr($cachedData, 4));
        $this->dataLoadedFromCache[md5(get_class($dataAdapter) . serialize($parameters))] = true;
        return $data;
    }

    /**
     * @param \hergot\databroker\DataAdapter\DataAdapterInterface $dataAdapter
     * @param array $parameters
     * @return string
     */
    private function getCachedData(DataAdapterInterface $dataAdapter, array $parameters) {
        $settings = $this->getSettingsForAdapter($dataAdapter);        
        $cacheKey = $this->getCacheKeyForAdapter($dataAdapter, $parameters);
        if (!$settings->getBackend() instanceof Cache\CacheBackendInterface) {
            throw new Cache\CachePluginException('Missing cache backend for data adapter "' . get_class($dataAdapter) . '"', Cache\CachePluginException::MISSING_BACKEND);
        }
        return $settings->getBackend()->read($cacheKey, null);
    }

    /**
     * @param \hergot\databroker\DataAdapter\DataAdapterInterface $dataAdapter
     * @param array $parameters
     * @param string $value
     * @throws Cache\CachePluginException
     */
    private function saveCacheData(DataAdapterInterface $dataAdapter, array $parameters, $value) {
        $settings = $this->getSettingsForAdapter($dataAdapter);        
        $cacheKey = $this->getCacheKeyForAdapter($dataAdapter, $parameters);
        if (!$settings->getBackend() instanceof Cache\CacheBackendInterface) {
            throw new Cache\CachePluginException('Missing cache backend for data adapter "' . get_class($dataAdapter) . '"', Cache\CachePluginException::MISSING_BACKEND);
        }
        if (!is_int($settings->getLifeTime())) {
            throw new Cache\CachePluginException('Missing cache lifetime for data adapter "' . get_class($dataAdapter) . '"', Cache\CachePluginException::MISSING_LIFETIME);
        }
        $settings->getBackend()->write($cacheKey, $value, $settings->getLifeTime());        
    }
    
    /**
     * @param \hergot\databroker\DataAdapter\DataAdapterInterface $dataAdapter
     * @return bool
     */
    private function isAdapterCacheable(DataAdapterInterface $dataAdapter) {
        return $this->getSettingsForAdapter($dataAdapter)->isCacheable() === true;
    }

    /**
     * @param \hergot\databroker\DataAdapter\DataAdapterInterface $dataAdapter
     * @param array $parameters
     * @return string
     */
    private function getCacheKeyForAdapter(DataAdapterInterface $dataAdapter, array $parameters) {
        return str_replace('\\', ':', get_class($dataAdapter)) . ':' . md5(serialize($parameters));
    }
    
    /**
     * @param \hergot\databroker\DataAdapter\DataAdapterInterface $dataAdapter
     * @return \hergot\databroker\Plugin\Cache\CacheSetting
     */
    private function getSettingsForAdapter(DataAdapterInterface $dataAdapter) {
        $result = new Cache\CacheSetting();
        $className = get_class($dataAdapter);
        foreach ($this->adapterCacheSettings as $mask => $cacheSetting) {
            $transformedMask = '@^' . str_replace('\\*', '.*', preg_quote($mask, '@')) . '$@';
            if (preg_match($transformedMask, $className)) {
                $result->merge($cacheSetting);
            }
        }
        return $result;        
    }
    
}