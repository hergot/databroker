<?php

namespace hergot\databroker\test\DataAdapter;

use hergot\databroker\DataAdapter\DataAdapterSimpleLoader;

class DataAdapterSimpleLoaderTest extends \PHPUnit_Framework_TestCase {
    
    public function testInstantiate() {
        $loader = new DataAdapterSimpleLoader('\hergot\databroker\test\mock\DataAdapter');
        $loader->instantiate('TestAdapter');
    }

    /**
     * @expectedException \UnexpectedValueException
     */
    public function testInstantiateInterfaceException() {
        $loader = new DataAdapterSimpleLoader('\hergot\databroker\test\mock\DataAdapter');
        $loader->instantiate('TestAdapterNoDataAdapterInterface');
    }
    
}