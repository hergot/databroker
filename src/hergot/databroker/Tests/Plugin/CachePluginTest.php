<?php

namespace hergot\databroker\Tests\Plugin;

use hergot\databroker\Plugin\CachePlugin;
use hergot\databroker\Plugin\Cache\FailStrategyResult;

class CachePluginTest extends \PHPUnit_Framework_TestCase {
    public function testRunBeforeExecuteNotCacheable() {
        $cachePlugin = new CachePlugin();
        $dataAdapterMock = $this->getMock('\hergot\databroker\DataAdapter\DataAdapterInterface');
        $result = null;
        $this->assertEquals(null, $cachePlugin->runBeforeExecute($dataAdapterMock, array(), $result));
    }
    
    public function testRunBeforeExecuteCached() {
        $cachePlugin = new CachePlugin();
        $result = null;        
        $dataAdapterMock = $this->getMock('\hergot\databroker\DataAdapter\DataAdapterInterface');
        $backend = $this->getMock('\hergot\databroker\Plugin\Cache\CacheBackendInterface');
        $backend->expects($this->once())
                ->method('read')
                ->will($this->returnCallback(function ($name, $defaultValue) {
                    $this->assertContains('Mock_DataAdapterInterface', $name);
                    return pack("N", time() + 10) . serialize('test');
                }));
        $cachePlugin->setup('*')
                ->setCacheable(true)
                ->setBackend($backend);
        $this->assertEquals('test', $cachePlugin->runBeforeExecute($dataAdapterMock, array(), $result));
    }

    public function testRunBeforeExecuteRefreshTimeExpired() {
        $cachePlugin = new CachePlugin();
        $result = null;        
        $dataAdapterMock = $this->getMock('\hergot\databroker\DataAdapter\DataAdapterInterface');
        $backend = $this->getMock('\hergot\databroker\Plugin\Cache\CacheBackendInterface');
        $backend->expects($this->once())
                ->method('read')
                ->will($this->returnCallback(function ($name, $defaultValue) {
                    $this->assertContains('Mock_DataAdapterInterface', $name);
                    return pack("N", time() - 10) . serialize('test');
                }));
        $cachePlugin->setup('*')
                ->setCacheable(true)
                ->setBackend($backend);
        $this->assertEquals(null, $cachePlugin->runBeforeExecute($dataAdapterMock, array(), $result));
    }

    public function testRunBeforeExecuteDataNotCached() {
        $cachePlugin = new CachePlugin();
        $result = null;        
        $dataAdapterMock = $this->getMock('\hergot\databroker\DataAdapter\DataAdapterInterface');
        $backend = $this->getMock('\hergot\databroker\Plugin\Cache\CacheBackendInterface');
        $backend->expects($this->once())
                ->method('read')
                ->will($this->returnCallback(function ($name, $defaultValue) {
                    $this->assertContains('Mock_DataAdapterInterface', $name);
                    return $defaultValue;
                }));
        $cachePlugin->setup('*')
                ->setCacheable(true)
                ->setBackend($backend);
        $this->assertEquals(null, $cachePlugin->runBeforeExecute($dataAdapterMock, array(), $result));
    }

    /**
     * @expectedException \hergot\databroker\Plugin\Cache\CachePluginException
     * @expectedExceptionCode \hergot\databroker\Plugin\Cache\CachePluginException::MISSING_BACKEND
     */
    public function testRunBeforeExecuteMissingCacheBackend() {
        $cachePlugin = new CachePlugin();
        $result = null;        
        $dataAdapterMock = $this->getMock('\hergot\databroker\DataAdapter\DataAdapterInterface');
        $cachePlugin->setup('*')
                ->setCacheable(true);
        $this->assertEquals(null, $cachePlugin->runBeforeExecute($dataAdapterMock, array(), $result));
    }

    public function testRunAfterExecuteNotCacheable() {
        $cachePlugin = new CachePlugin();
        $dataAdapterMock = $this->getMock('\hergot\databroker\DataAdapter\DataAdapterInterface');
        $result = null;
        $this->assertEquals(null, $cachePlugin->runAfterExecute($dataAdapterMock, array(), $result));
    }
    
    public function testRunAfterExecuteSaveToCache() {
        $backend = $this->getMock('\hergot\databroker\Plugin\Cache\CacheBackendInterface');
        $backend->expects($this->once())
                ->method('write')
                ->will($this->returnCallback(function ($name, $value, $lifeTime) {
                    $this->assertEquals(10, $lifeTime);
                    $this->assertContains('Mock_DataAdapterInterface', $name);
                    $this->assertEquals(pack("N", time() + 10) . serialize('test'), $value);
                }));
                
        $cachePlugin = new CachePlugin();
        $cachePlugin->setup('*')
                ->setLifeTime(10)
                ->setBackend($backend)
                ->setCacheable(true);
        $dataAdapterMock = $this->getMock('\hergot\databroker\DataAdapter\DataAdapterInterface');
        $result = 'test';
        $this->assertEquals('test', $cachePlugin->runAfterExecute($dataAdapterMock, array(), $result));        
    }

    public function testRunAfterExecuteFailStrategy() {
        $backend = $this->getMock('\hergot\databroker\Plugin\Cache\CacheBackendInterface');
        $backend->expects($this->once())
                ->method('write')
                ->will($this->returnCallback(function ($name, $value, $lifeTime) {
                    $this->assertEquals(10, $lifeTime);
                    $this->assertContains('Mock_DataAdapterInterface', $name);
                    $this->assertEquals(pack("N", time() + 20) . serialize('cachedTest'), $value);
                }));

        $failStrategyMock = $this->getMock('\hergot\databroker\Plugin\Cache\FailStrategyInterface');
        $failStrategyMock->expects($this->once())
                ->method('getFailStrategyResult')
                ->will($this->returnCallback(function($dataAdapter, $parameters, $result, $cacheDataCallback, $exception) {
                    return new FailStrategyResult(20, 'cachedTest');
                }));
        $cachePlugin = new CachePlugin();
        $cachePlugin->setup('*')
                ->setLifeTime(10)
                ->setBackend($backend)
                ->setFailStrategy($failStrategyMock)
                ->setCacheable(true);
        $dataAdapterMock = $this->getMock('\hergot\databroker\DataAdapter\DataAdapterInterface');
        $result = 'test';
        $this->assertEquals('cachedTest', $cachePlugin->runAfterExecute($dataAdapterMock, array(), $result, new \Exception('test')));        
    }

    public function testRunAfterExecuteFailStrategyCachedData() {
        $backend = $this->getMock('\hergot\databroker\Plugin\Cache\CacheBackendInterface');
        $backend->expects($this->once())
                ->method('write')
                ->will($this->returnCallback(function ($name, $value, $lifeTime) {
                    $this->assertEquals(10, $lifeTime);
                    $this->assertContains('Mock_DataAdapterInterface', $name);
                    $this->assertEquals(pack("N", time() + 20) . serialize('originalCachedData'), $value);
                }));
        $backend->expects($this->once())
                ->method('read')
                ->will($this->returnCallback(function ($key, $defaultValue) {
                    $this->assertContains('Mock_DataAdapterInterface', $key);
                    return 'originalCachedData';
                }));

        $failStrategyMock = $this->getMock('\hergot\databroker\Plugin\Cache\FailStrategyInterface');
        $failStrategyMock->expects($this->once())
                ->method('getFailStrategyResult')
                ->will($this->returnCallback(function($dataAdapter, $parameters, $result, $cacheDataCallback, $exception) {
                    return new FailStrategyResult(20, call_user_func($cacheDataCallback));
                }));
        $cachePlugin = new CachePlugin();
        $cachePlugin->setup('*')
                ->setLifeTime(10)
                ->setBackend($backend)
                ->setFailStrategy($failStrategyMock)
                ->setCacheable(true);
        $dataAdapterMock = $this->getMock('\hergot\databroker\DataAdapter\DataAdapterInterface');
        $result = 'test';
        $this->assertEquals('originalCachedData', $cachePlugin->runAfterExecute($dataAdapterMock, array(), $result, new \Exception('test')));        
    }

    /**
     * @expectedException \RuntimeException
     */
    public function testRunAfterExecuteFailStrategyBadResult() {
        $backend = $this->getMock('\hergot\databroker\Plugin\Cache\CacheBackendInterface');

        $failStrategyMock = $this->getMock('\hergot\databroker\Plugin\Cache\FailStrategyInterface');
        $failStrategyMock->expects($this->once())
                ->method('getFailStrategyResult')
                ->will($this->returnCallback(function($dataAdapter, $parameters, $result, $cacheDataCallback, $exception) {
                    return null;
                }));
        $cachePlugin = new CachePlugin();
        $cachePlugin->setup('*')
                ->setLifeTime(10)
                ->setBackend($backend)
                ->setFailStrategy($failStrategyMock)
                ->setCacheable(true);
        $dataAdapterMock = $this->getMock('\hergot\databroker\DataAdapter\DataAdapterInterface');
        $result = 'test';
        $cachePlugin->runAfterExecute($dataAdapterMock, array(), $result, new \Exception('test'));
    }
    
    public function testRunAfterExecuteAlreadyLoadedFromCache() {
        $backend = $this->getMock('\hergot\databroker\Plugin\Cache\CacheBackendInterface');
        $backend->expects($this->once())
                ->method('read')
                ->will($this->returnCallback(function ($name, $defaultValue=null) {
                    $this->assertContains('Mock_DataAdapterInterface', $name);
                    return pack("N", time() + 10) . serialize('test');
                }));
                
        $cachePlugin = new CachePlugin();
        $cachePlugin->setup('*')
                ->setLifeTime(10)
                ->setBackend($backend)
                ->setCacheable(true);
        $dataAdapterMock = $this->getMock('\hergot\databroker\DataAdapter\DataAdapterInterface');
        $this->assertEquals('test', $cachePlugin->runBeforeExecute($dataAdapterMock, array(), null));
        $this->assertEquals(null, $cachePlugin->runAfterExecute($dataAdapterMock, array(), null));
    }

    /**
     * @expectedException \hergot\databroker\Plugin\Cache\CachePluginException
     * @expectedExceptionCode \hergot\databroker\Plugin\Cache\CachePluginException::MISSING_BACKEND
     */
    public function testRunAfterExecuteSaveToCacheMissingBackend() {                
        $cachePlugin = new CachePlugin();
        $cachePlugin->setup('*')
                ->setLifeTime(10)
                ->setCacheable(true);
        $dataAdapterMock = $this->getMock('\hergot\databroker\DataAdapter\DataAdapterInterface');
        $cachePlugin->runAfterExecute($dataAdapterMock, array(), null);
    }

    /**
     * @expectedException \hergot\databroker\Plugin\Cache\CachePluginException
     * @expectedExceptionCode \hergot\databroker\Plugin\Cache\CachePluginException::MISSING_LIFETIME
     */
    public function testRunAfterExecuteSaveToCacheMissingLifetime() {                
        $backend = $this->getMock('\hergot\databroker\Plugin\Cache\CacheBackendInterface');
        $cachePlugin = new CachePlugin();
        $cachePlugin->setup('*')
                ->setBackend($backend)
                ->setCacheable(true);
        $dataAdapterMock = $this->getMock('\hergot\databroker\DataAdapter\DataAdapterInterface');
        $cachePlugin->runAfterExecute($dataAdapterMock, array(), null);
    }
    
}