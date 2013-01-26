<?php

namespace hergot\databroker\Tests\Plugin\Cache;

use hergot\databroker\Plugin\Cache\FailStrategyResult;

class FailStrategyResultTest extends \PHPUnit_Framework_TestCase {

    public function testValue() {
        $fsr = new FailStrategyResult(5, 'test');
        $this->assertEquals('test', $fsr->getValue());
        $fsr->setValue('test2');
        $this->assertEquals('test2', $fsr->getValue());
    }
    
    public function testRefreshTime() {
        $fsr = new FailStrategyResult(5, 'test');
        $this->assertEquals(5, $fsr->getRefreshTime());
        $fsr->setRefreshTime(1);
        $this->assertEquals(1, $fsr->getRefreshTime());
    }

    /**
     * @expectedException \InvalidArgumentException
     */
    public function testRefreshTimeNotInteger() {
        new FailStrategyResult('test', 'test');
    }

    /**
     * @expectedException \InvalidArgumentException
     */
    public function testRefreshTimeLessThanZero() {
        new FailStrategyResult(-1, 'test');
    }

    /**
     * @expectedException \InvalidArgumentException
     */
    public function testRefreshTimeSetterNotInteger() {
        $fsr = new FailStrategyResult(5, 'test');
        $fsr->setRefreshTime('test');
    }

    /**
     * @expectedException \InvalidArgumentException
     */
    public function testRefreshTimeSetterLessThanZero() {
        $fsr = new FailStrategyResult(5, 'test');
        $fsr->setRefreshTime(-1);
    }
    
}