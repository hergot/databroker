<?php

namespace hergot\databroker\Tests\Plugin\Cache;

use hergot\databroker\Plugin\Cache\CacheSetting;

class CacheSettingsTest extends \PHPUnit_Framework_TestCase {

    public function testSetRefreshTime() {
        $cs = new CacheSetting;
        $this->assertEquals(null, $cs->getRefreshTime());
        $cs->setRefreshTime(1);
        $this->assertEquals(1, $cs->getRefreshTime());
        $cs->setRefreshTime(null);
        $this->assertEquals(null, $cs->getRefreshTime());
    }
    
    /**
     * @expectedException \InvalidArgumentException
     */
    public function testSetRefreshTimeNotInteger() {
        $cs = new CacheSetting;
        $cs->setRefreshTime('test');        
    }
    
    public function testMerge() {
        $cs = new CacheSetting;
        $cs->setLifeTime(1);
        $cs2 = new CacheSetting;
        $cs2->setLifeTime(2);
        $cs->merge($cs2);
        $this->assertEquals(2, $cs->getLifeTime());
    }
    
    public function testMergeNullInMergedSettings() {
        $cs = new CacheSetting;
        $cs->setLifeTime(1);
        $cs2 = new CacheSetting;
        $cs2->setLifeTime(null);
        $cs->merge($cs2);
        $this->assertEquals(1, $cs->getLifeTime());        
    }
    
    public function testSetFailStrategy() {
        $cs = new CacheSetting;
        $failStrategyMock = $this->getMock('\hergot\databroker\Plugin\Cache\FailStrategyInterface');
        $cs->setFailStrategy($failStrategyMock);
        $this->assertEquals($failStrategyMock, $cs->getFailStrategy());
    }
}