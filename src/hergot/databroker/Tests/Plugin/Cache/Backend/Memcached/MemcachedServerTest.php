<?php

namespace hergot\databroker\Tests\Plugin\Cache\Memcached;

use hergot\databroker\Plugin\Cache\Backend\Memcached\MemcachedServer;

class MemcachedServerTest extends \PHPUnit_Framework_TestCase {
    
    /**
     * @expectedException \InvalidArgumentException
     */
    public function testPortNotInteger() {
        new MemcachedServer('localhost', 'abc');
    }

    /**
     * @expectedException \InvalidArgumentException
     */
    public function testPortOutOfRange() {
        new MemcachedServer('localhost', -1);
    }

    /**
     * @expectedException \InvalidArgumentException
     */
    public function testPortOutOfRange2() {
        new MemcachedServer('localhost', 100000);
    }

    /**
     * @expectedException \InvalidArgumentException
     */
    public function testWeightNotInteger() {
        new MemcachedServer('localhost', 10, 'abc');
    }

    /**
     * @expectedException \InvalidArgumentException
     */
    public function testWeightLessThanZero() {
        new MemcachedServer('localhost', 10, -1);
    }
    
    public function testHost() {
        $ms = new MemcachedServer('localhost');
        $this->assertEquals('localhost', $ms->getHost());
        $ms->setHost('test');
        $this->assertEquals('test', $ms->getHost());
    }

    /**
     * @expectedException \InvalidArgumentException
     */
    public function testSetPortNotInteger() {
        $ms = new MemcachedServer('localhost');
        $ms->setPort('abc');
    }

    /**
     * @expectedException \InvalidArgumentException
     */
    public function testSetPortOutOfRange() {
        $ms = new MemcachedServer('localhost');
        $ms->setPort(-1);
    }

    /**
     * @expectedException \InvalidArgumentException
     */
    public function testSetPortOutOfRange2() {
        $ms = new MemcachedServer('localhost');
        $ms->setPort(100000);
    }

    public function testSetPort() {
        $ms = new MemcachedServer('localhost');
        $ms->setPort(10);
        $this->assertEquals(10, $ms->getPort());
    }

    /**
     * @expectedException \InvalidArgumentException
     */
    public function testSetWeightNotInteger() {
        $ms = new MemcachedServer('localhost');
        $ms->setWeight('abc');
    }

    /**
     * @expectedException \InvalidArgumentException
     */
    public function testSetWeightLessThanZero() {
        $ms = new MemcachedServer('localhost');
        $ms->setWeight(-1);
    }

    public function testSetWeight() {
        $ms = new MemcachedServer('localhost');
        $ms->setWeight(10);
        $this->assertEquals(10, $ms->getWeight());
    }
    
}