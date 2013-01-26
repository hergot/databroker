<?php

namespace hergot\databroker\Tests\Plugin\Cache;

use hergot\databroker\Plugin\Cache\FailStrategy\ConstValueFailStrategy;
use hergot\databroker\Plugin\Cache\FailStrategyResult;

class ConstValueFailStrategyTest extends \PHPUnit_Framework_TestCase {

    public function testGetFailStrategyResult() {
        $fs = new ConstValueFailStrategy(5);
        $dataAdapterMock = $this->getMock('\hergot\databroker\DataAdapter\DataAdapterInterface');
        $this->assertEquals(new FailStrategyResult(5, null), 
                $fs->getFailStrategyResult($dataAdapterMock, array(), 'test', function() { return 'test'; }));
        $fs = new ConstValueFailStrategy(10, 'test');
        $dataAdapterMock = $this->getMock('\hergot\databroker\DataAdapter\DataAdapterInterface');
        $this->assertEquals(new FailStrategyResult(10, 'test'), 
                $fs->getFailStrategyResult($dataAdapterMock, array(), null, function() { return null; }));
    }
    
}
