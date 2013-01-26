<?php

namespace hergot\databroker\Tests\Plugin\Cache;

class FailStrategyTest extends \PHPUnit_Framework_TestCase {

    public function testReturnValue() {
        $m = $this->getMockForAbstractClass('\hergot\databroker\Plugin\Cache\FailStrategy\FailStrategy');
        $m->setReturnValue('test');
        $this->assertEquals('test', $m->getReturnValue());
    }
    
    public function testRefreshTime() {
        $m = $this->getMockForAbstractClass('\hergot\databroker\Plugin\Cache\FailStrategy\FailStrategy');
        $m->setRefreshTime(10);
        $this->assertEquals(10, $m->getRefreshTime());
    }

    /**
     * @expectedException \InvalidArgumentException
     */
    public function testRefreshTimeNotInteger() {
        $m = $this->getMockForAbstractClass('\hergot\databroker\Plugin\Cache\FailStrategy\FailStrategy');
        $m->setRefreshTime('test');
    }

    /**
     * @expectedException \InvalidArgumentException
     */
    public function testRefreshTimeLessThanZero() {
        $m = $this->getMockForAbstractClass('\hergot\databroker\Plugin\Cache\FailStrategy\FailStrategy');
        $m->setRefreshTime(-1);
    }
    
}
