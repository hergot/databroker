<?php

namespace hergot\databroker\Tests\DataAdapter;

use hergot\databroker\DataAdapter\ParameterCollection;
use hergot\databroker\DataAdapter\Parameter;

class ParameterCollectionTest extends \PHPUnit_Framework_TestCase {
    
    public function testParameter() {
        $p = new Parameter('test');
        $pc = new ParameterCollection();
        $pc->addParameter($p);
        $this->assertEquals(array('test' => $p), $pc->getParameters());
        $this->assertEquals($p, $pc->get('test'));
    }

    /**
     * @expectedException \InvalidArgumentException
     */
    public function testAddSameParameter() {
        $p = new Parameter('test');
        $pc = new ParameterCollection();
        $pc->addParameter($p);
        $pc->addParameter($p);
    }
    
    public function testToArray() {
        $p = new Parameter('test');
        $p2 = new Parameter('test2');
        $p2->setValue('test');
        $pc = new ParameterCollection();
        $pc->addParameter($p);
        $pc->addParameter($p2);
        $this->assertEquals(array('test' => null, 'test2' => 'test'), $pc->toArray());
    }
    
}