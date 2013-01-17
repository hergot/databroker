<?php

namespace hergot\databroker\Tests\Plugin\Cache;

use hergot\databroker\Plugin\Cache\FailStrategy;

class FailStrategyTest extends \PHPUnit_Framework_TestCase {

    public function testReturnValue() {
        $fs = new FailStrategy(FailStrategy::RETURN_TYPE_VALUE, 5);
        $this->assertEquals(null, $fs->getReturnValue());
        $fs->setReturnValue('test');
        $this->assertEquals('test', $fs->getReturnValue());
    }
    
    public function testRefreshTime() {
        $fs = new FailStrategy(FailStrategy::RETURN_TYPE_VALUE, 5);
        $this->assertEquals(5, $fs->getRefreshTime());
        $fs->setRefreshTime(10);
        $this->assertEquals(10, $fs->getRefreshTime());        
    }

    /**
     * @expectedException \InvalidArgumentException
     */
    public function testRefreshTimeNotInteger() {
        new FailStrategy(FailStrategy::RETURN_TYPE_VALUE, 'test');
    }

    /**
     * @expectedException \InvalidArgumentException
     */
    public function testRefreshTimeNotIntegerInSetter() {
        $fs = new FailStrategy(FailStrategy::RETURN_TYPE_VALUE, 5);
        $fs->setRefreshTime('test');
    }

    /**
     * @expectedException \InvalidArgumentException
     */
    public function testRefreshTimeLessThanZero() {
        new FailStrategy(FailStrategy::RETURN_TYPE_VALUE, -1);
    }

    /**
     * @expectedException \InvalidArgumentException
     */
    public function testRefreshTimeLessThanZeroInSetter() {
        $fs = new FailStrategy(FailStrategy::RETURN_TYPE_VALUE, 5);
        $fs->setRefreshTime(-1);
    }
    
    public function testReturnType() {
        $fs = new FailStrategy(FailStrategy::RETURN_TYPE_VALUE, 5);
        $this->assertEquals(FailStrategy::RETURN_TYPE_VALUE, $fs->getReturnType());
        $fs->setReturnType(FailStrategy::RETURN_TYPE_CACHED_VALUE);
        $this->assertEquals(FailStrategy::RETURN_TYPE_CACHED_VALUE, $fs->getReturnType());
    }

    /**
     * @expectedException \InvalidArgumentException
     */
    public function testReturnTypeUnknown() {
        new FailStrategy('test', 5);
    }

    /**
     * @expectedException \InvalidArgumentException
     */
    public function testReturnTypeUnknownInSetter() {
        $fs = new FailStrategy(FailStrategy::RETURN_TYPE_VALUE, 5);
        $fs->setReturnType('test');
    }
    
    
    
}