<?php

namespace hergot\databroker\Tests\Plugin\Cache;

use hergot\databroker\Plugin\Cache\Backend\MemcachedCacheBackend;
use hergot\databroker\Plugin\Cache\Backend\Memcached\MemcachedServer;

class MemcachedCacheBackendTest extends \PHPUnit_Framework_TestCase {
    
    const TEST_MEMCACHE_SERVER = '127.0.0.1';
    const TEST_MEMCACHE_PORT = 11211;
    
    /**
     * @var \Memcached
     */
    private $memcache;
    
    public function setUp() {
        parent::setUp();
        $this->memcache = new \Memcached;
        $this->memcache->addServer(self::TEST_MEMCACHE_SERVER, self::TEST_MEMCACHE_PORT);
        $this->memcache->flush();
        if (!$this->memcache->set('test', 1, time() + 100)) {
            throw new \RuntimeException('Cannot save item to memcache. ' . $this->memcache->getResultMessage());
        }
    }
    
    /**
     * @expectedException \RuntimeException
     * @group memcached
     */
    public function testReadNoServerConfiguration() {
        $mc = new MemcachedCacheBackend();
        $mc->read('test');
    }

    /**
     * @group memcached
     */
    public function testRead() {
        $mc = new MemcachedCacheBackend();
        $mc->addServer(new MemcachedServer(self::TEST_MEMCACHE_SERVER));
        $this->assertEquals(null, $mc->read('not_exists', null));        
        $this->assertEquals(1, $mc->read('test', null));
    }

    /**
     * @group memcached
     */
    public function testWrite() {
        $mc = new MemcachedCacheBackend();
        $mc->addServer(new MemcachedServer(self::TEST_MEMCACHE_SERVER));
        $mc->write('test2', '123', time() + 10);
        $this->assertEquals('123', $mc->read('test2', null));
    }

    /**
     * @group memcached
     * @expectedException \InvalidArgumentException
     */
    public function testWriteLifetimeNotInteger() {
        $mc = new MemcachedCacheBackend();
        $mc->addServer(new MemcachedServer(self::TEST_MEMCACHE_SERVER));
        $mc->write('test2', '123', 'test');
    }

    /**
     * @group memcached
     * @expectedException \InvalidArgumentException
     */
    public function testWriteLifetimeLessThanZero() {
        $mc = new MemcachedCacheBackend();
        $mc->addServer(new MemcachedServer(self::TEST_MEMCACHE_SERVER));
        $mc->write('test2', '123', -1);
    }

    /**
     * @group memcached
     */
    public function testInvalidate() {
        $mc = new MemcachedCacheBackend();
        $mc->addServer(new MemcachedServer(self::TEST_MEMCACHE_SERVER));
        $mc->invalidate('test');
        $this->assertFalse($this->memcache->get('test'));
        $this->assertEquals(\Memcached::RES_NOTFOUND, $this->memcache->getResultCode());
    }
    
}