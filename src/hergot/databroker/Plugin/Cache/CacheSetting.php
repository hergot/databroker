<?php

namespace hergot\databroker\Plugin\Cache;

class CacheSetting {
    
    /**
     * @var bool|null
     */
    private $isCacheable;
    /**
     * @var \hergot\databroker\Plugin\Cache\CacheBackendInterface|null
     */
    private $backend;
    /**
     * @var integer|null
     */
    private $lifeTime;
    /**
     * @var integer|null
     */
    private $refreshTime;
    
    /**
     * @var FailStrategyInterface
     */
    private $failStrategy;
    
    public function __construct() {
        $this->isCacheable = null;
        $this->backend = null;
        $this->lifeTime = null;
        $this->refreshTime = null;
        $this->failStrategy = null;
    }

    /**
     * @param \hergot\databroker\Plugin\Cache\CacheSetting $cacheSetting
     */
    public function merge(CacheSetting $cacheSetting) {
        $this->isCacheable = $cacheSetting->isCacheable() !== null ? $cacheSetting->isCacheable() : $this->isCacheable;
        $this->backend = $cacheSetting->getBackend() !== null ? $cacheSetting->getBackend() : $this->backend;
        $this->lifeTime = $cacheSetting->getLifeTime() !== null ? $cacheSetting->getLifeTime() : $this->lifeTime;
        $this->refreshTime = $cacheSetting->getRefreshTime() !== null ? $cacheSetting->getRefreshTime() : $this->refreshTime;
        $this->failStrategy = $cacheSetting->getFailStrategy() !== null ? $cacheSetting->getFailStrategy() : $this->failStrategy;
    }

    /**
     * @return FailStrategyInterface
     */
    public function getFailStrategy() {
        return $this->failStrategy;
    }

    /**
     * @param \hergot\databroker\Plugin\Cache\FailStrategyInterface $failStrategy
     * @return \hergot\databroker\Plugin\Cache\CacheSetting
     */
    public function setFailStrategy(FailStrategyInterface $failStrategy) {
        $this->failStrategy = $failStrategy;
        return $this;
    }
    
    /**
     * @return bool|null
     */
    public function isCacheable() {
        return $this->isCacheable;
    }

    /**
     * @param bool $isCacheable
     * @return \hergot\databroker\Plugin\Cache\CacheSetting
     */
    public function setCacheable($isCacheable) {
        $this->isCacheable = (bool)$isCacheable;
        return $this;
    }

    /**
     * @return \hergot\databroker\Plugin\Cache\CacheBackendInterface|null
     */
    public function getBackend() {
        return $this->backend;
    }

    /**
     * @param \hergot\databroker\Plugin\Cache\CacheBackendInterface $backend
     * @return \hergot\databroker\Plugin\Cache\CacheSetting
     */
    public function setBackend(CacheBackendInterface $backend) {
        $this->backend = $backend;
        return $this;
    }

    /**
     * @return integer|null
     */
    public function getLifeTime() {
        return $this->lifeTime;
    }

    /**
     * @param integer $lifeTime
     * @return \hergot\databroker\Plugin\Cache\CacheSetting
     */
    public function setLifeTime($lifeTime) {
        $this->lifeTime = $lifeTime;
        return $this;
    }

    /**
     * @return integer|null
     */
    public function getRefreshTime() {
        return $this->refreshTime;
    }

    /**
     * @param integer|null $refreshTime
     * @return \hergot\databroker\Plugin\Cache\CacheSetting
     * @throws \InvalidArgumentException
     */
    public function setRefreshTime($refreshTime) {
        if ($refreshTime !== null && !is_int($refreshTime)) {
            throw new \InvalidArgumentException('Refresh time must be integer or null');
        }
        $this->refreshTime = $refreshTime;
        return $this;
    }

}