<?php

namespace hergot\databroker\test;

use hergot\databroker\DataBroker;

class DataBrokerTest extends \PHPUnit_Framework_TestCase {
    public function testExecute() {
        $adapterMock = $this->getMock('\hergot\DataAdapter\DataAdapterInterface', array('getParameters', 'fetch'));
        $adapterMock->expects($this->once())
                ->method('getParameters')
                ->will($this->returnValue(array('parameter' => array('required' => true))));

        $adapterMock->expects($this->once())
                ->method('fetch')
                ->will($this->returnValue('test'));
        
        $loaderMock = $this->getMock('\hergot\DataAdapter\DataAdapterLoaderInterface', array('instantiate'));
        $loaderMock->expects($this->once())
                ->method('instantiate')
                ->will($this->returnCallback(function($name) use ($adapterMock) {
                    $this->assertEquals('testAdapter', $name);
                    
                    return $adapterMock;
                }));
        $databroker = new DataBroker($loaderMock);
        $this->assertEquals('test', $databroker->execute('testAdapter', array('parameter' => 1)));
    }
    
    /**
     * @expectedException \hergot\databroker\DataBrokerException
     * @expectedExceptionCode \hergot\databroker\DataBrokerException::MISSING_REQUIRED_PARAMETER
     */
    public function testExecuteMissingRequiredParameter() {
        $adapterMock = $this->getMock('\hergot\DataAdapter\DataAdapterInterface', array('getParameters'));
        $adapterMock->expects($this->once())
                ->method('getParameters')
                ->will($this->returnValue(array('parameter' => array('required' => true))));
        
        $loaderMock = $this->getMock('\hergot\DataAdapter\DataAdapterLoaderInterface', array('instantiate'));
        $loaderMock->expects($this->once())
                ->method('instantiate')
                ->will($this->returnCallback(function($name) use ($adapterMock) {
                    $this->assertEquals('testAdapter', $name);
                    
                    return $adapterMock;
                }));
        $databroker = new DataBroker($loaderMock);
        $databroker->execute('testAdapter', array());
    }

    /**
     * @expectedException \UnexpectedValueException
     */
    public function testExecuteDataAdapterParameterNonArrayDefinition() {
        $adapterMock = $this->getMock('\hergot\DataAdapter\DataAdapterInterface', array('getParameters'));
        $adapterMock->expects($this->once())
                ->method('getParameters')
                ->will($this->returnValue(array('parameter' => 1)));
        
        $loaderMock = $this->getMock('\hergot\DataAdapter\DataAdapterLoaderInterface', array('instantiate'));
        $loaderMock->expects($this->once())
                ->method('instantiate')
                ->will($this->returnCallback(function($name) use ($adapterMock) {
                    $this->assertEquals('testAdapter', $name);
                    
                    return $adapterMock;
                }));
        $databroker = new DataBroker($loaderMock);
        $databroker->execute('testAdapter', array());
    }

    public function testExecuteDataAdapterParameterDefaultValue() {
        $adapterMock = $this->getMock('\hergot\DataAdapter\DataAdapterInterface', array('getParameters', 'fetch'));
        $adapterMock->expects($this->once())
                ->method('getParameters')
                ->will($this->returnValue(array('parameter' => array('default' => 'test'))));

        $adapterMock->expects($this->once())
                ->method('fetch')
                ->will($this->returnCallback(function(array $parameters) {
                    $this->assertEquals('test', $parameters['parameter']);
                    return 'test';
                }));
        
        $loaderMock = $this->getMock('\hergot\DataAdapter\DataAdapterLoaderInterface', array('instantiate'));
        $loaderMock->expects($this->once())
                ->method('instantiate')
                ->will($this->returnCallback(function($name) use ($adapterMock) {
                    $this->assertEquals('testAdapter', $name);
                    
                    return $adapterMock;
                }));
        $databroker = new DataBroker($loaderMock);
        $this->assertEquals('test', $databroker->execute('testAdapter', array()));
    }

    public function testExecuteDataAdapterParameterNoDefinition() {
        $adapterMock = $this->getMock('\hergot\DataAdapter\DataAdapterInterface', array('getParameters', 'fetch'));
        $adapterMock->expects($this->once())
                ->method('getParameters')
                ->will($this->returnValue(array('parameter')));

        $adapterMock->expects($this->once())
                ->method('fetch')
                ->will($this->returnCallback(function(array $parameters) {
                    $this->assertEquals(array('parameter' => null), $parameters);
                    return 'test';
                }));
        
        $loaderMock = $this->getMock('\hergot\DataAdapter\DataAdapterLoaderInterface', array('instantiate'));
        $loaderMock->expects($this->once())
                ->method('instantiate')
                ->will($this->returnCallback(function($name) use ($adapterMock) {
                    $this->assertEquals('testAdapter', $name);
                    
                    return $adapterMock;
                }));
        $databroker = new DataBroker($loaderMock);
        $this->assertEquals('test', $databroker->execute('testAdapter', array()));
    }

    /**
     * @expectedException \hergot\databroker\DataBrokerException
     * @expectedExceptionCode \hergot\databroker\DataBrokerException::MISMATCH_PARAMETER_TYPE
     */
    public function testExecuteDataAdapterParameterTypeMismatch() {
        $adapterMock = $this->getMock('\hergot\DataAdapter\DataAdapterInterface', array('getParameters'));
        $adapterMock->expects($this->once())
                ->method('getParameters')
                ->will($this->returnValue(array('parameter' => array('type' => 'integer'))));
        
        $loaderMock = $this->getMock('\hergot\DataAdapter\DataAdapterLoaderInterface', array('instantiate'));
        $loaderMock->expects($this->once())
                ->method('instantiate')
                ->will($this->returnCallback(function($name) use ($adapterMock) {
                    $this->assertEquals('testAdapter', $name);
                    
                    return $adapterMock;
                }));
        $databroker = new DataBroker($loaderMock);
        $databroker->execute('testAdapter', array('parameter' => 'test'));
    }

    /**
     * @expectedException \hergot\databroker\DataBrokerException
     * @expectedExceptionCode \hergot\databroker\DataBrokerException::MISMATCH_PARAMETER_INTERFACE
     */
    public function testExecuteDataAdapterParameterInterfaceMismatch() {
        $adapterMock = $this->getMock('\hergot\DataAdapter\DataAdapterInterface', array('getParameters'));
        $adapterMock->expects($this->once())
                ->method('getParameters')
                ->will($this->returnValue(array('parameter' => array('interface' => '\stdClass'))));
        
        $loaderMock = $this->getMock('\hergot\DataAdapter\DataAdapterLoaderInterface', array('instantiate'));
        $loaderMock->expects($this->once())
                ->method('instantiate')
                ->will($this->returnCallback(function($name) use ($adapterMock) {
                    $this->assertEquals('testAdapter', $name);
                    
                    return $adapterMock;
                }));
        $databroker = new DataBroker($loaderMock);
        $databroker->execute('testAdapter', array('parameter' => 'test'));
    }
    
}
