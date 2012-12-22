<?php

namespace hergot\databroker\test\DataAdapter;

use hergot\databroker\DataAdapter\DataAdapterCallbackLoader;

class DataAdapterCallbackLoaderTest extends \PHPUnit_Framework_TestCase {
    
    public function testInstantiate() {
        $loader = new DataAdapterCallbackLoader(function($name) {
            $this->assertEquals('testAdapter', $name);
            $mock = $this->getMock('\hergot\databroker\DataAdapter\DataAdapterInterface');
            return $mock;
        });
        $loader->instantiate('testAdapter');
    }
    
    /**
     * @expectedException \UnexpectedValueException
     */
    public function testInstantiateNotObject() {
        $loader = new DataAdapterCallbackLoader(function($name) {
            $this->assertEquals('testAdapter', $name);
            return 1;
        });
        $loader->instantiate('testAdapter');
    }

    /**
     * @expectedException \UnexpectedValueException
     */
    public function testInstantiateNotDataAdapterInterface() {
        $loader = new DataAdapterCallbackLoader(function($name) {
            $this->assertEquals('testAdapter', $name);
            $mock = $this->getMock('\stdClass');
            return $mock;
        });
        $loader->instantiate('testAdapter');
    }
    
}