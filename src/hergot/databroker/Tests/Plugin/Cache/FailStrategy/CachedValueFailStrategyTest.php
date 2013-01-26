<?php

namespace hergot\databroker\Tests\Plugin\Cache;

use hergot\databroker\Plugin\Cache\FailStrategy\CachedValueFailStrategy;
use hergot\databroker\Plugin\Cache\FailStrategyResult;

class CachedValueFailStrategyTest extends \PHPUnit_Framework_TestCase {

    public function testGetFailStrategyResult() {
        $fs = new CachedValueFailStrategy(5);
        $dataAdapterMock = $this->getMock('\hergot\databroker\DataAdapter\DataAdapterInterface');
        $this->assertEquals(new FailStrategyResult(5, 'test'), 
                $fs->getFailStrategyResult($dataAdapterMock, array(), 'test', function() { return 'test'; }));
    }
    
}
