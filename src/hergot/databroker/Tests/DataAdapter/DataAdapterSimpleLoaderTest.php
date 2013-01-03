<?php

namespace hergot\databroker\Tests\DataAdapter;

use hergot\databroker\DataAdapter\DataAdapterSimpleLoader;

class DataAdapterSimpleLoaderTest extends \PHPUnit_Framework_TestCase {
    
    public function testInstantiate() {
        $loader = new DataAdapterSimpleLoader('\hergot\databroker\Tests\Mock\DataAdapter');
        $loader->instantiate('TestAdapter');
    }

    /**
     * @expectedException \InvalidArgumentException
     */
    public function testInstantiateInterfaceException() {
        $loader = new DataAdapterSimpleLoader('\hergot\databroker\Tests\Mock\DataAdapter');
        $loader->instantiate('TestAdapterNoDataAdapterInterface');
    }
    
}