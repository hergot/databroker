<?php

namespace hergot\databroker\Tests\Plugin\Cache;

use hergot\databroker\Plugin\Cache\Backend\APCCacheBackend;

class APCCacheBackendTest extends \PHPUnit_Framework_TestCase {

    /**
     * @group apc
     * @throws \RuntimeException
     */
    public function testInvalidate() {
        $ac = new APCCacheBackend();
        $result = apc_store('test', 123, 10);
        if ($result !== true) {
            throw new \RuntimeException('Cannot store test value to APC');
        }
        $ac->invalidate('test');
        $this->assertFalse(apc_exists('test'));
    }

    /**
     * @group apc
     * @throws \RuntimeException
     */
    public function testRead() {
        $ac = new APCCacheBackend();
        if (apc_exists('test')) {
            $result = apc_delete('test');
            if ($result !== true) {
                throw new \RuntimeException('Cannot remove test value from APC');
            }
        }
        $this->assertEquals(null, $ac->read('test', null));
        $result = apc_store('test', 123, 10);
        if ($result !== true) {
            throw new \RuntimeException('Cannot store test value to APC');
        }
        $this->assertEquals(123, $ac->read('test'));
    }

    /**
     * @group apc
     * @throws \RuntimeException
     */
    public function testWrite() {
        $ac = new APCCacheBackend();
        if (apc_exists('test')) {
            $result = apc_delete('test');
            if ($result !== true) {
                throw new \RuntimeException('Cannot remove test value from APC');
            }
        }
        $this->assertFalse(apc_exists('test'));
        $expire = time() + 1;
        $ac->write('test', '1', $expire);
        $this->assertTrue(apc_exists('test'));
        $this->assertEquals(1, apc_fetch('test'));
        $storedExpiry = $this->apc_expire('test');
        $this->assertGreaterThan($expire - 2, $storedExpiry);
        $this->assertLessThan($expire + 2, $storedExpiry);
    }

    /**
     * @group apc
     * @expectedException \InvalidArgumentException
     */
    public function testWriteLifetimeNotInteger() {
        $ac = new APCCacheBackend();
        $ac->write('test', '1', 'abc');
    }

    /**
     * @group apc
     * @expectedException \InvalidArgumentException
     */
    public function testWriteLifetimeNegativeInteger() {
        $ac = new APCCacheBackend();
        $ac->write('test', '1', -1);
    }
    
    /**
     * Return a key's expiration time.
     *
     * @param string $key The name of the key.
     *
     * @return mixed Returns false when no keys are cached or when the key
     *               does not exist. Returns int 0 when the key never expires
     *               (ttl = 0) or an integer (unix timestamp) otherwise.
     */
    private function apc_expire($key) {
        $cache = apc_cache_info('user');
        if (empty($cache['cache_list'])) {
            return false;
        }
        foreach ($cache['cache_list'] as $entry) {
            if ($entry['info'] != $key) {
                continue;
            }
            if ($entry['ttl'] == 0) {
                return 0;
            }
            $expire = $entry['creation_time'] + $entry['ttl'];
            return $expire;
        }
        return false;
    }

}